<?php

declare(strict_types=1);

namespace App\Application\Command;

use App\Application\Service\ProductService;
use App\Domain\Product\Product;
use App\Domain\ValueObject\UuidV7;
use RdKafka\Conf;
use RdKafka\KafkaConsumer;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ConsumeProductsCommand extends Command
{
    protected static $defaultName = 'app:consume-products';

    public function __construct(private ProductService $productService)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setDescription(description: 'Consumes product messages from Kafka and saves them.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $conf = new Conf();
        $conf->set(name: 'group.id', value: 'product_consumers');
        $conf->set(name: 'metadata.broker.list', value: 'kafka:9092');
        $conf->set(name: 'auto.offset.reset', value: 'earliest');

        $consumer = new KafkaConsumer(conf: $conf);
        $consumer->subscribe(topics: ['products']);

        $output->writeln('Starting Kafka consumer for products...');

        while (true) {
            $message = $consumer->consume(timeout_ms: 120 * 1000);
            switch ($message->err) {
                case RD_KAFKA_RESP_ERR_NO_ERROR:
                    $payload = json_decode(json: $message->payload, associative: true);
                    if (!$this->isValidProductPayload(payload: $payload)) {
                        $output->writeln('Invalid product payload');
                        break;
                    }

                    $product = new Product(
                        name: $payload['name'],
                        weight: $payload['measurments']['weight'],
                        height: $payload['measurments']['height'],
                        width: $payload['measurments']['width'],
                        length: $payload['measurments']['lenght'],
                        description: $payload['description'] ?? null,
                        cost: $payload['cost'],
                        tax: $payload['tax'],
                        version: $payload['version'],
                    );
                    $product->setId(id: new UuidV7(uuid: (string) $payload['id']));

                    $this->productService->saveProduct(product: $product);
                    $output->writeln("Product {$payload['id']} saved successfully");
                    break;
                case RD_KAFKA_RESP_ERR__PARTITION_EOF:
                    $output->writeln('No more messages; waiting...');
                    break;
                case RD_KAFKA_RESP_ERR__TIMED_OUT:
                    $output->writeln('Timed out waiting for messages');
                    break;

                default:
                    $output->writeln('Error: ' . $message->errstr());

                    return Command::FAILURE;
            }
        }

        return Command::SUCCESS;
    }

    private function isValidProductPayload(array $payload): bool
    {
        return isset($payload['id'], $payload['name'], $payload['measurments'], $payload['cost'], $payload['tax'], $payload['version'])
            && isset($payload['measurments']['weight'], $payload['measurments']['height'], $payload['measurments']['width'], $payload['measurments']['lenght']);
    }
}
