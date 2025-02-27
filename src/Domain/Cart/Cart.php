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

    /** @var Collection|CartItem[] */
    private Collection $items;

    public function __construct(int $userId)
    {
        $this->userId = $userId;
        $this->items = new ArrayCollection();
    }

    public function addItem(CartItem $item): void
    {
        if ($this->items->count() >= 20) {
            throw new DomainException('В заказе не может быть более 20 позиций');
        }

        foreach ($this->items as $existingItem) {
            if ($existingItem->getProduct()->getId() === $item->getProduct()->getId()) {
                $existingItem->increaseQuantity($item->getQuantity());

                return;
            }
        }

        $this->items->add($item);
    }

    public function removeItem(int $productId): void
    {
        foreach ($this->items as $key => $item) {
            if ($item->getProduct()->getId() === $productId) {
                $this->items->remove($key);

                return;
            }
        }
    }

    public function getItems(): Collection
    {
        return $this->items;
    }

    public function getTotalQuantity(): int
    {
        return array_reduce(
            $this->items->toArray(),
            static fn(int $carry, CartItem $item) => $carry + $item->getQuantity(),
            0,
        );
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function addProduct(Product $product, int $quantity): void
    {
        if ($product->getId() === null) {
            throw new InvalidArgumentException('Product ID cannot be null');
        }

        if ($quantity <= 0) {
            throw new InvalidArgumentException('Quantity must be greater than zero');
        }

        foreach ($this->items as $existingItem) {
            if ($existingItem instanceof CartItem && $existingItem->getProduct()->getId() === $product->getId()) {
                $existingItem->increaseQuantity($quantity);

                return;
            }
        }

        $cartItem = new CartItem($product->getId(), $quantity);
        $this->addItem($cartItem);
    }
}
