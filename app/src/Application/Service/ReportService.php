<?php

namespace App\Application\Service;

use App\Domain\Cart\Cart;
use App\Domain\Cart\Repository\CartRepositoryInterface;
use Symfony\Component\Filesystem\Filesystem;
use Ramsey\Uuid\Uuid;

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
                $reportLines[] = json_encode([
                    'product_id' => $item->getProductId(),
                    'quantity' => $item->getQuantity(),
                    'user_id' => $cart->getUserId(),
                ]);
            }
        }

        $reportContent = implode("\n", $reportLines);
        $filePath = sprintf('var/reports/report_%s.jsonl', $reportId);

        try {
            $this->filesystem->dumpFile($filePath, $reportContent);
            return $reportId;
        } catch (\Exception $e) {
            throw new \RuntimeException('Unable to write report file: ' . $e->getMessage());
        }
    }
}