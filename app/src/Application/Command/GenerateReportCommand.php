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
        $output->writeln('Starting Kafka consumer for report generation...');

        $conf = new Conf();
        $conf->set(name: 'group.id', value: 'report_generators');
        $conf->set(name: 'metadata.broker.list', value: 'kafka:9092');
        $conf->set(name: 'auto.offset.reset', value: 'earliest');

        $consumer = new KafkaConsumer(conf: $conf);
        $consumer->subscribe(topics: ['report_generation']);

        try {
            while (true) {
                $message = $consumer->consume(timeout_ms: 120 * 1000);
                switch ($message->err) {
                    case RD_KAFKA_RESP_ERR_NO_ERROR:
                        $payload = json_decode(json: $message->payload, associative: true);
                        if (!isset($payload['reportId'])) {
                            $output->writeln('Invalid message payload: missing reportId');

                            continue 2; // Здесь изменено continue на continue 2
                        }
                        $reportId = $payload['reportId'];
                        $output->writeln("Processing report ID: {$reportId}");

                        try {
                            $this->reportService->generateSalesReport();
                            $this->sendReportResult(reportId: $reportId, result: 'success');
                            $output->writeln("Report {$reportId} generated successfully");
                        } catch (Exception $e) {
                            $this->sendReportResult(reportId: $reportId, result: 'fail', errorMessage: $e->getMessage());
                            $output->writeln("Failed to generate report {$reportId}: " . $e->getMessage());
                        }
                        break;
                    case RD_KAFKA_RESP_ERR__PARTITION_EOF:
                        $output->writeln('No more messages; waiting for more...');
                        break;
                    case RD_KAFKA_RESP_ERR__TIMED_OUT:
                        $output->writeln('Timed out waiting for messages');
                        break;

                    default:
                        throw new Exception(message: $message->errstr(), code: $message->err);
                }
            }
        } catch (Exception $e) {
            $output->writeln('Error: ' . $e->getMessage());

            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }

    private function sendReportResult(string $reportId, string $result, ?string $errorMessage = null): void
    {
        $producer = new Producer();
        $producer->addBrokers(broker_list: 'kafka:9092');
        $topic = $producer->newTopic(topic_name: 'report_result');

        $message = ['reportId' => $reportId, 'result' => $result];
        if ($result === 'fail') {
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
