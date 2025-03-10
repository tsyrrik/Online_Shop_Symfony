<?php

declare(strict_types=1);

namespace App\Infrastructure\Api\Controller;

use Ramsey\Uuid\Uuid;
use RdKafka\Producer;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class ReportController
{
    private Producer $kafkaProducer;

    public function __construct(Producer $kafkaProducer)
    {
        $this->kafkaProducer = $kafkaProducer;
    }

    #[Route('/api/report/generate', name: 'generate_report', methods: ['POST'])]
    public function generateReport(): JsonResponse
    {
        $reportId = Uuid::uuid4()->toString();
        $topic = $this->kafkaProducer->newTopic('report_generation');
        $topic->produce(RD_KAFKA_PARTITION_UA, 0, json_encode(['reportId' => $reportId]));
        $this->kafkaProducer->flush(10000); // Ждем отправки сообщения

        return new JsonResponse([
            'reportId' => $reportId,
            'result' => 'success',
        ]);
    }
}
