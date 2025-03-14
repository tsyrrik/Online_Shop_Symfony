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
use Doctrine\Common\Collections\Collection;
use Exception;

class CheckoutHandler
{
    public function __construct(
        private CartRepositoryInterface $cartRepository,
        private ProductRepositoryInterface $productRepository,
        private OrderRepositoryInterface $orderRepository,
    ) {}

    public function handle(CheckoutCommand $command): void
    {
        // Получаем корзину пользователя
        $cart = $this->cartRepository->getOpenCartForUser($command->getUserId());
        if (!$cart || $cart->getItems()->isEmpty()) {
            throw new Exception('Cart is empty or not found');
        }

        // Создаем типизированную коллекцию для элементов заказа
        /** @var ArrayCollection<int, OrderItem> */
        $orderItems = new ArrayCollection();

        foreach ($cart->getItems() as $cartItem) {
            // Получаем продукт по ID из корзины
            $product = $this->productRepository->findById($cartItem->getProductId());
            if ($product === null || $product->getId() === null) {
                throw new Exception('Product not found or has no ID for ID: ' . ($cartItem->getProductId()?->toString() ?? 'unknown'));
            }

            // Добавляем элемент заказа в коллекцию
            /** @psalm-assert !null $product */
            /** @psalm-assert !null $product->getId() */
            $orderItems->add(new OrderItem(
                $product->getId(),
                $product->getName(),
                $cartItem->getQuantity(),
                $product->getCost(),
            ));
        }

        // Создаем и сохраняем заказ
        /** @var Order */
        $order = new Order(
            $command->getUserId(),
            /** @var Collection<int, OrderItem> */
            $orderItems,
            $command->getDeliveryMethod(),
            $command->getOrderPhone(),
        );

        // Сохраняем заказ
        $this->orderRepository->save($order);


        // Обновляем корзину (если это требуется)
        $cart = $this->cartRepository->getCartForUser($command->getUserId()) ?? new Cart(userId: $command->getUserId());
        $this->cartRepository->saveCart($command->getUserId(), $cart);
    }
}
