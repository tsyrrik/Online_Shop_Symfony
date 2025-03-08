<?php

declare(strict_types=1);

namespace App\Domain\Cart;

use App\Domain\Product\Product;
use App\Enum\OrderStatus;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use DomainException;
use InvalidArgumentException;
use Ramsey\Uuid\UuidInterface;

final class Cart
{
    private UuidInterface $userId;

    /** @var Collection<int, CartItem> */
    private Collection $items;

    private OrderStatus $status;

    public function __construct(UuidInterface $userId)
    {
        $this->userId = $userId;
        $this->items = new ArrayCollection();
        $this->status = OrderStatus::PAID;
    }

    public function addItem(CartItem $item): void
    {
        if ($this->items->count() >= 20) {
            throw new DomainException(message: 'There cannot be more than 20 items in an order');
        }

        foreach ($this->items as $existingItem) {
            if ($existingItem->getProductId() === $item->getProductId()) {
                $existingItem->increaseQuantity(amount: $item->getQuantity());

                return;
            }
        }

        $this->items->add($item);
    }

    public function removeItem(UuidInterface $productId): void
    {
        foreach ($this->items as $key => $item) {
            if ($item->getProductId()->equals($productId)) {
                $this->items->remove($key);

                return;
            }
        }
    }

    /** @return Collection<int, CartItem> */
    public function getItems(): Collection
    {
        return $this->items;
    }

    public function getTotalQuantity(): int
    {
        return array_reduce(
            $this->items->toArray(),
            /** @param int $carry @param CartItem $item */
            static fn(int $carry, CartItem $item) => $carry + $item->getQuantity(),
            0,
        );
    }

    public function getUserId(): UuidInterface
    {
        return $this->userId;
    }

    public function addProduct(Product $product, int $quantity): void
    {
        $productId = $product->getId();
        if ($productId === null) {
            throw new InvalidArgumentException('Product ID cannot be null');
        }

        if ($quantity <= 0) {
            throw new InvalidArgumentException(message: 'Quantity must be greater than zero');
        }

        foreach ($this->items as $existingItem) {
            if ($existingItem->getProductId() === $productId) {
                $existingItem->increaseQuantity(amount: $quantity);

                return;
            }
        }

        $cartItem = new CartItem(productId: $productId, quantity: $quantity);
        $this->addItem(item: $cartItem);
    }

    public function setStatus(OrderStatus $status): void
    {
        $this->status = $status;
    }

    public function isCompleted(): bool
    {
        return $this->status === OrderStatus::DELIVERED || $this->status === OrderStatus::CANCELLED;
    }

    public function getStatus(): OrderStatus
    {
        return $this->status;
    }
}
