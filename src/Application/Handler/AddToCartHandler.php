<?php

declare(strict_types=1);

namespace App\Application\Handler;

use App\Application\Command\AddToCartCommand;
use App\Domain\Cart\Cart;
use App\Domain\Cart\Repository\CartRepositoryInterface;
use App\Domain\Product\Repository\ProductRepositoryInterface;
use Exception;

readonly class AddToCartHandler
{
    public function __construct(private ProductRepositoryInterface $productRepository, private CartRepositoryInterface $cartRepository) {}

    public function handle(AddToCartCommand $command): void
    {
        $product = $this->productRepository->findById($command->getProductId());
        if (!$product) {
            throw new Exception('Товар не найден');
        }

        $cart = $this->cartRepository->getCartForUser($command->getUserId()) ?? new Cart($command->getUserId());
        $cart->addProduct($product, $command->getQuantity());

        $this->cartRepository->saveCart($command->getUserId(), $cart);
    }
}
