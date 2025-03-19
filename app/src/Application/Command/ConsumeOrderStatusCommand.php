<?php

declare(strict_types=1);

namespace App\Application\Command;

use App\Domain\Order\Repository\OrderRepositoryInterface;
use App\Domain\ValueObject\UuidV7;
use App\Enum\OrderStatus;
use RdKafka\Conf;
use RdKafka\KafkaConsumer;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ConsumeOrderStatusCommand extends Command
{
    protected static $defaultName = 'app:consume-order-status';

    public function __construct(private OrderRepositoryInterface $orderRepository)
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $consumer = new KafkaConsumer(conf: new Conf());
        $consumer->subscribe(topics: ['order_status_update']);

        while (true) {
            $message = $consumer->consume(timeout_ms: 120 * 1000);
            if ($message->err === RD_KAFKA_RESP_ERR_NO_ERROR) {
                $payload = json_decode(json: $message->payload, associative: true);
                $orderId = $payload['orderId'] ?? null;
                $newStatus = $payload['status'] ?? null;

                if ($orderId && $newStatus) {
                    $order = $this->orderRepository->findById(new UuidV7(uuid: $orderId));
                    if ($order) {
                        $order->setStatus(status: OrderStatus::from(value: $newStatus));
                        $this->orderRepository->save($order);
                        $output->writeln("Order {$orderId} status updated to {$newStatus}");
                    }
                }
            }
        }

        return Command::SUCCESS;
    }
}
