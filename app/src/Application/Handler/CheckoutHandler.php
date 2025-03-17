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

final readonly class CheckoutHandler
{
    public function __construct(
        private CartRepositoryInterface $cartRepository,
        private ProductRepositoryInterface $productRepository,
        private OrderRepositoryInterface $orderRepository,
    ) {}

    public function handle(CheckoutCommand $command): void
    {
        $cart = $this->cartRepository->getOpenCartForUser((string) $command->userId);
        if (!$cart || $cart->getItems()->isEmpty()) {
            throw new Exception('Cart is empty or not found');
        }

        /** @var ArrayCollection<int, OrderItem> $orderItems */
        $orderItems = new ArrayCollection();

        foreach ($cart->getItems() as $cartItem) {
            $product = $this->productRepository->findById((string) $cartItem->getProductId());
            if ($product === null) {
                throw new Exception('Product not found for ID: ' . $cartItem->getProductId()->toString());
            }

            $productId = $product->getId();
            if ($productId === null) {
                throw new Exception('Product ID cannot be null');
            }
            $orderItems->add(new OrderItem(
                $productId,
                $product->getName(),
                $cartItem->getQuantity(),
                $product->getCost(),
            ));
        }

        $order = new Order(
            $command->userId,
            $orderItems,
            $command->deliveryMethod,
            $command->orderPhone,
        );

        $this->orderRepository->save($order);

        $cart = $this->cartRepository->getCartForUser((string) $command->userId)
            ?? new Cart(userId: $command->userId);
        $this->cartRepository->saveCart((string) $command->userId, $cart);
    }
}
