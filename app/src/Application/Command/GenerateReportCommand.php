<?php

declare(strict_types=1);

namespace App\Application\Command;

use App\Application\Service\ReportService;
use Exception;
use RdKafka\Conf;
use RdKafka\KafkaConsumer;
use RdKafka\Producer;
use RuntimeException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class GenerateReportCommand extends Command
{
    protected static $defaultName = 'app:generate-report';

    public function __construct(private ReportService $reportService)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setDescription(description: 'Consumes report generation requests from Kafka and generates sales reports.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        // Configure Kafka consumer
        $conf = new Conf();
        $conf->set(name: 'group.id', value: 'report_generators');
        $conf->set(name: 'metadata.broker.list', value: 'kafka:9092');
        $conf->set(name: 'auto.offset.reset', value: 'earliest');

        $consumer = new KafkaConsumer(conf: $conf);
        $consumer->subscribe(topics: ['report_generation']);

        while (true) {
            $message = $consumer->consume(timeout_ms: 120 * 1000);
            if ($message->err === RD_KAFKA_RESP_ERR_NO_ERROR) {
                $payload = json_decode(json: $message->payload, associative: true);
                $reportId = $payload['reportId'] ?? null;

                if ($reportId === null) {
                    $output->writeln('Invalid payload: missing reportId');

                    continue;
                }

                try {
                    $filePath = $this->reportService->generateSalesReport();
                    $this->sendReportResult(reportId: $reportId, result: 'success', filePath: $filePath);
                } catch (Exception $e) {
                    $this->sendReportResult(reportId: $reportId, result: 'fail', errorMessage: $e->getMessage());
                }
            }
        }

        return Command::SUCCESS;
    }

    private function sendReportResult(string $reportId, string $result, ?string $filePath = null, ?string $errorMessage = null): void
    {
        $producer = new Producer();
        $producer->addBrokers(broker_list: 'kafka:9092');
        $topic = $producer->newTopic(topic_name: 'report_result');

        $message = ['reportId' => $reportId, 'result' => $result];
        if ($result === 'success' && $filePath) {
            $message['filePath'] = $filePath;
        } elseif ($result === 'fail' && $errorMessage) {
            $message['detail'] = ['error' => $errorMessage];
        }

        $encodedMessage = json_encode(value: $message);
        if ($encodedMessage === false) {
            throw new RuntimeException(message: 'Failed to encode message to JSON');
        }

        $topic->produce(partition: RD_KAFKA_PARTITION_UA, msgflags: 0, payload: $encodedMessage);
        $producer->flush(timeout_ms: 10000);
    }
}
