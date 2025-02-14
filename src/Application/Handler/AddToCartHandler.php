<?php

namespace App\Application\Handler;

use App\Application\Command\AddToCartCommand;
use App\Domain\Product\Repository\ProductRepositoryInterface;
use App\Domain\Cart\Repository\CartRepositoryInterface;
use App\Domain\Cart\Cart;
readonly class AddToCartHandler
{
    public function __construct(private ProductRepositoryInterface $productRepository, private CartRepositoryInterface $cartRepository) {}

    public function handle(AddToCartCommand $command): void
    {
        $product = $this->productRepository->findById($command->getProductId());
        if (!$product) {
            throw new \Exception('Товар не найден');
        }

        $cart = $this->cartRepository->getCartForUser($command->getUserId());
        if (!$cart) {
            $cart = new Cart();
        }

        $cart->addProduct($product, $command->getQuantity());
        $this->cartRepository->saveCart($command->getUserId(), $cart);
    }
}
