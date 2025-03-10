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
        $topic = $this->kafkaProducer->newTopic(topic_name: 'report_generation');

        $messageData = ['reportId' => $reportId];

        $jsonPayload = json_encode(value: $messageData);

        if ($jsonPayload === false) {
            return new JsonResponse(data: ['error' => 'Failed to encode data as JSON'], status: 500);
        }

        // Отправляем сообщение в Kafka
        $topic->produce(partition: RD_KAFKA_PARTITION_UA, msgflags: 0, payload: $jsonPayload);
        $this->kafkaProducer->flush(timeout_ms: 10000); // Ждем отправки сообщения

        return new JsonResponse(data: [
            'reportId' => $reportId,
            'result' => 'success',
        ]);
    }
}
