<?php

declare(strict_types=1);

namespace App\Application\Handler;

use App\Application\Command\AddToCartCommand;
use App\Domain\Cart\Cart;
use App\Domain\Cart\Repository\CartRepositoryInterface;
use App\Domain\Product\Repository\ProductRepositoryInterface;
use Exception;

final readonly class AddToCartHandler
{
    public function __construct(
        private ProductRepositoryInterface $productRepository,
        private CartRepositoryInterface $cartRepository,
    ) {}

    public function handle(AddToCartCommand $command): void
    {
        $product = $this->productRepository->findById($command->productId->toString());
        if (!$product) {
            throw new Exception('Product not found');
        }

        $cart = $this->cartRepository->getCartForUser($command->userId->toString())
            ?? new Cart(userId: $command->userId);

        $cart->addProduct(product: $product, quantity: $command->quantity);

        $this->cartRepository->saveCart($command->userId->toString(), $cart);
    }
}
