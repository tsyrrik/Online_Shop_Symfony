<?php

declare(strict_types=1);

namespace App\Domain\Cart;

use App\Domain\Product\Product;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use DomainException;
use InvalidArgumentException;

class Cart
{
    private int $userId;

    /** @var Collection<int, CartItem> */
    private Collection $items;

    public function __construct(int $userId)
    {
        $this->userId = $userId;
        $this->items = new ArrayCollection();
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

    public function removeItem(int $productId): void
    {
        foreach ($this->items as $key => $item) {
            if ($item->getProductId() === $productId) {
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
            array: $this->items->toArray(), // @var CartItem[]
            callback: static fn(int $carry, CartItem $item) => $carry + $item->getQuantity(),
            initial: 0,
        );
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function addProduct(Product $product, int $quantity): void
    {
        $productId = $product->getId();
        if ($productId === null) {
            throw new InvalidArgumentException(message: 'Product ID cannot be null');
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
}
