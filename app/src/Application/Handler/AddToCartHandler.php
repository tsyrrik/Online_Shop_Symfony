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
    public function __construct(private ProductRepositoryInterface $productRepository, private CartRepositoryInterface $cartRepository) {}

    public function handle(AddToCartCommand $command): void
    {
        $product = $this->productRepository->findById($command->getProductId());
        if (!$product) {
            throw new Exception(message: 'Товар не найден');
        }

        $cart = $this->cartRepository->getCartForUser($command->getUserId()) ?? new Cart(userId: $command->getUserId());
        $cart->addProduct(product: $product, quantity: $command->getQuantity());

        $this->cartRepository->saveCart($command->getUserId(), $cart);
    }
}
