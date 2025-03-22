<?php

declare(strict_types=1);

namespace App\Application\Handler;

use App\Application\Command\CheckoutCommand;
use App\Domain\Cart\Cart;
use App\Domain\Cart\Repository\CartRepositoryInterface;
use App\Domain\Order\Order;
use App\Domain\Order\OrderItem;
use App\Domain\Order\Repository\OrderRepositoryInterface;
use App\Domain\Product\Repository\ProductRepositoryInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Exception;
use RdKafka\Producer;
use RuntimeException;

final readonly class CheckoutHandler
{
    public function __construct(
        private CartRepositoryInterface $cartRepository,
        private ProductRepositoryInterface $productRepository,
        private OrderRepositoryInterface $orderRepository,
        private Producer $kafkaProducer,
    ) {}

    public function handle(CheckoutCommand $command): void
    {
        $cart = $this->cartRepository->getOpenCartForUser((string) $command->userId);
        if (!$cart || $cart->getItems()->isEmpty()) {
            throw new Exception(message: 'Cart is empty or not found');
        }

        /** @var ArrayCollection<int, OrderItem> $orderItems */
        $orderItems = new ArrayCollection();

        foreach ($cart->getItems() as $cartItem) {
            $product = $this->productRepository->findById((string) $cartItem->getProductId());
            if ($product === null) {
                throw new Exception(message: 'Product not found for ID: ' . $cartItem->getProductId()->toString());
            }

            $productId = $product->getId();
            if ($productId === null) {
                throw new Exception(message: 'Product ID cannot be null');
            }
            $orderItems->add(element: new OrderItem(
                productId: $productId,
                productName: $product->getName(),
                quantity: $cartItem->getQuantity(),
                priceAtPurchase: $product->getCost(),
            ));
        }

        $order = new Order(
            userId: $command->userId,
            items: $orderItems,
            deliveryMethod: $command->deliveryMethod,
            orderPhone: $command->orderPhone,
        );

        $this->orderRepository->save($order);

        // Отправляем уведомление в Kafka
        $this->sendOrderNotification(order: $order, notificationType: 'requires_payment');

        $cart = $this->cartRepository->getCartForUser((string) $command->userId)
            ?? new Cart(userId: $command->userId);
        $this->cartRepository->saveCart((string) $command->userId, $cart);
    }

    private function sendOrderNotification(Order $order, string $notificationType): void
    {
        $topic = $this->kafkaProducer->newTopic(topic_name: 'order_notifications');

        $message = [
            'type' => 'sms',
            'userPhone' => $order->getOrderPhone(),
            'notificationType' => $notificationType,
            'orderNum' => $order->getId()->toString(),
            'orderItems' => array_map(callback: static fn(OrderItem $item) => [
                'name' => $item->getProductName(),
                'cost' => $item->getPriceAtPurchase(),
                'additionalInfo' => null,
            ], array: $order->getItems()->toArray()),
            'deliveryType' => $order->getDeliveryMethod()->value,
            'deliveryAddress' => [
                'kladrId' => null,
                'fullAddress' => null,
            ],
        ];

        $jsonPayload = json_encode(value: $message);
        if ($jsonPayload === false) {
            throw new RuntimeException(message: 'Failed to encode message to JSON');
        }

        $topic->produce(partition: RD_KAFKA_PARTITION_UA, msgflags: 0, payload: $jsonPayload);
        $this->kafkaProducer->flush(timeout_ms: 10000);
    }
}
