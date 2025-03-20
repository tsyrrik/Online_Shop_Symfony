<?php

declare(strict_types=1);

namespace App\Application\Service;

use App\Domain\Cart\Repository\CartRepositoryInterface;
use App\Domain\Product\Repository\ProductRepositoryInterface;
use App\Domain\ValueObject\UuidV7;
use RuntimeException;
use Symfony\Component\Filesystem\Filesystem;

use function sprintf;

final class ReportService
{
    public function __construct(
        private CartRepositoryInterface $cartRepository,
        private ProductRepositoryInterface $productRepository,
        private Filesystem $filesystem,
    ) {}

    public function generateSalesReport(): string
    {
        $reportId = (new UuidV7())->toString();
        $carts = $this->cartRepository->findCompletedCarts();
        $reportLines = [];

        foreach ($carts as $cart) {
            foreach ($cart->getItems() as $item) {
                $product = $this->productRepository->findById($item->getProductId());
                if ($product === null) {
                    throw new RuntimeException(message: "Product with ID {$item->getProductId()} not found");
                }

                $reportLines[] = json_encode(value: [
                    'product_name' => $product->getName(),
                    'price' => $product->getCost(),
                    'amount' => $item->getQuantity(),
                    'user' => ['id' => $cart->getUserId()],
                ]);
            }
        }

        $filePath = sprintf('var/reports/report_%s.jsonl', $reportId);
        $this->filesystem->dumpFile(filename: $filePath, content: implode(separator: "\n", array: $reportLines));

        return $reportId;
    }
}
