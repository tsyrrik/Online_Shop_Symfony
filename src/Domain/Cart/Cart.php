<?php

namespace App\Domain\Cart;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

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
            throw new \DomainException("В заказе не может быть более 20 позиций");
        }

        // Проверяем, есть ли уже такой продукт в корзине
        foreach ($this->items as $existingItem) {
            if ($existingItem->getProduct()->getId() === $item->getProduct()->getId()) {
                $existingItem->increaseQuantity($item->getQuantity());
                return;
            }
        }

        // Если продукта нет, добавляем новый элемент
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
            0
        );
    }

    public function getUserId(): int
    {
        return $this->userId;
    }
}
