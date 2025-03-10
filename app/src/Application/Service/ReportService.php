<?php

declare(strict_types=1);

namespace App\Application\Service;

use App\Domain\Cart\Repository\CartRepositoryInterface;
use App\Domain\Product\Repository\ProductRepositoryInterface;
use Exception;
use Ramsey\Uuid\Uuid;
use RuntimeException;
use Symfony\Component\Filesystem\Filesystem;

use function sprintf;

class ReportService
{
    public function __construct(
        private CartRepositoryInterface $cartRepository,
        private ProductRepositoryInterface $productRepository,
        private Filesystem $filesystem,
    ) {}

    public function generateSalesReport(): string
    {
        $reportId = Uuid::uuid4()->toString();
        $carts = $this->cartRepository->findCompletedCarts();
        $reportLines = [];

        foreach ($carts as $cart) {
            foreach ($cart->getItems() as $item) {
                $product = $this->productRepository->findById($item->getProductId());
                if ($product === null) {
                    throw new RuntimeException("Product with ID {$item->getProductId()} not found");
                }

                $reportLines[] = json_encode([
                    'product_name' => $product->getName(),
                    'price' => $product->getCost(),
                    'amount' => $item->getQuantity(),
                    'user' => ['id' => $cart->getUserId()],
                ]);
            }
        }

        $reportContent = implode("\n", $reportLines);
        $filePath = sprintf('var/reports/report_%s.jsonl', $reportId);

        try {
            $this->filesystem->dumpFile($filePath, $reportContent);

            return $reportId;
        } catch (Exception $e) {
            throw new RuntimeException('Unable to write report file: ' . $e->getMessage());
        }
    }
}
