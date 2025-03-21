<?php

declare(strict_types=1);

namespace App\Infrastructure\Api\DTO;

use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'ReportResult', title: 'ReportResult', description: 'Результат генерации отчета')]
final class ReportResultDTO extends BaseDTO
{
    #[OA\Property(type: 'string', format: 'uuid', example: '550e8400-e29b-41d4-a716-446655440000')]
    public string $reportId;

    #[OA\Property(type: 'string', example: 'success')]
    public string $result;

    #[OA\Property(type: 'string', nullable: true, example: 'var/reports/report_550e8400-e29b-41d4-a716-446655440000.jsonl')]
    public ?string $filePath;

    #[OA\Property(type: 'object', nullable: true, properties: [
        new OA\Property(property: 'error', type: 'string', example: 'Ошибка генерации'),
    ])]
    public ?array $detail;

    public function __construct(string $reportId, string $result, ?string $filePath = null, ?array $detail = null)
    {
        $this->reportId = $reportId;
        $this->result = $result;
        $this->filePath = $filePath;
        $this->detail = $detail;
    }
}
