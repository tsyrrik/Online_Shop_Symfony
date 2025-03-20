<?php

declare(strict_types=1);

namespace App\Infrastructure\Api\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use function sprintf;

class ReportDownloadController extends AbstractController
{
    #[Route('/api/report/download/{reportId}', name: 'download_report', methods: ['GET'])]
    public function download(string $reportId): Response
    {
        $filePath = sprintf('var/reports/report_%s.jsonl', $reportId);
        if (!file_exists(filename: $filePath)) {
            return new JsonResponse(data: ['error' => 'Report not found'], status: Response::HTTP_NOT_FOUND);
        }

        return new BinaryFileResponse(file: $filePath);
    }
}
