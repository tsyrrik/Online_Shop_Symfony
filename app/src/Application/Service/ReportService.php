<?php

declare(strict_types=1);

namespace App\Application\Service;

use App\Domain\Cart\Repository\CartRepositoryInterface;
use Exception;
use Ramsey\Uuid\Uuid;
use RuntimeException;
use Symfony\Component\Filesystem\Filesystem;

use function sprintf;

class ReportService
{
    public function __construct(private CartRepositoryInterface $cartRepository, private Filesystem $filesystem) {}

    public function generateSalesReport(): string
    {
        $reportId = Uuid::uuid4()->toString();
        $carts = $this->cartRepository->findCompletedCarts();
        $reportLines = [];

        foreach ($carts as $cart) {
            foreach ($cart->getItems() as $item) {
                $reportLines[] = json_encode(value: [
                    'product_id' => $item->getProductId(),
                    'quantity' => $item->getQuantity(),
                    'user_id' => $cart->getUserId(),
                ]);
            }
        }

        $reportContent = implode(separator: "\n", array: $reportLines);
        $filePath = sprintf('var/reports/report_%s.jsonl', $reportId);

        try {
            $this->filesystem->dumpFile(filename: $filePath, content: $reportContent);

            return $reportId;
        } catch (Exception $e) {
            throw new RuntimeException(message: 'Unable to write report file: ' . $e->getMessage());
        }
    }
}
