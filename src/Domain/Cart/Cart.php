<?php

declare(strict_types=1);

namespace App\Domain\Cart;

use App\Domain\Product\Product;

class Cart
{
    /**
     * @var CartItem[]
     */
    private array $items = [];

    public function addProduct(Product $product, int $quantity = 1): void
    {
        foreach ($this->items as $item) {
            if ($item->getProduct()->getId() === $product->getId()) {
                $item->increaseQuantity($quantity);

                return;
            }
        }
        $this->items[] = new CartItem($product, $quantity);
    }

    public function removeProduct(int $productId): void
    {
        $this->items = array_filter($this->items, static fn(CartItem $item) => $item->getProduct()->getId() !== $productId);
    }

    /**
     * Возвращает все элементы корзины.
     *
     * @return CartItem[]
     */
    public function getItems(): array
    {
        return $this->items;
    }
}
