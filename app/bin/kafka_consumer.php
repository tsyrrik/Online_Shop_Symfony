<?php

declare(strict_types=1);

use App\Application\Service\ReportService;
use App\Infrastructure\Repository\InMemoryCartRepository;
use RdKafka\Conf;
use RdKafka\KafkaConsumer;
use RdKafka\Producer;
use Symfony\Component\Filesystem\Filesystem;

require __DIR__ . '/../vendor/autoload.php';

$conf = new Conf();
$conf->set('group.id', 'report_consumer_group');
$conf->set('metadata.broker.list', 'kafka:9092');
$conf->set('auto.offset.reset', 'earliest');

$consumer = new KafkaConsumer($conf);
$consumer->subscribe(['report_generation']);

$reportService = new ReportService(
    new InMemoryCartRepository(),
    new Filesystem(),
    new Producer($conf),
);

echo "Starting Kafka consumer...\n";

while (true) {
    $message = $consumer->consume(120 * 1000);
    switch ($message->err) {
        case RD_KAFKA_RESP_ERR_NO_ERROR:
            $payload = json_decode($message->payload, true);
            echo 'Processing report ID: ' . $payload['reportId'] . "\n";
            $reportService->generateSalesReport($payload['reportId']);
            break;
        case RD_KAFKA_RESP_ERR__PARTITION_EOF:
            echo "No more messages; waiting...\n";
            break;

        default:
            echo 'Error: ' . $message->errstr() . "\n";
            break;
    }
}
