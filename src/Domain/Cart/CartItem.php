<?php

namespace App\Domain\Cart;

use App\Domain\Product\Product;
class CartItem
{
    private Product $product;
    private int $quantity;
    public function __construct(Product $product, int $quantity)
    {
        $this->product = $product;
        $this->quantity = $quantity;
    }
    public function getProduct(): Product
    {
        return $this->product;
    }
    public function getQuantity(): int
    {
        return $this->quantity;
    }
    public function increaseQuantity(int $amount = 1): void
    {
        $this->quantity += $amount;
    }
}
