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
        // Access productId directly and get UuidInterface
        $product = $this->productRepository->findById($command->productId->getUuid());
        if (!$product) {
            throw new Exception(message: 'Product not found');
        }

        // Access userId directly and get UuidInterface; create new Cart if none exists
        $cart = $this->cartRepository->getCartForUser($command->userId->getUuid())
            ?? new Cart(userId: $command->userId->getUuid());

        // Access quantity directly
        $cart->addProduct(product: $product, quantity: $command->quantity);

        // Save cart with UuidInterface
        $this->cartRepository->saveCart($command->userId->getUuid(), $cart);
    }
}
