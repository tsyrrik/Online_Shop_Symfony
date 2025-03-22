<?php

declare(strict_types=1);

namespace App\Application\Command;

use App\Domain\Order\Order;
use App\Domain\Order\Repository\OrderRepositoryInterface;
use App\Domain\ValueObject\UuidV7;
use App\Enum\OrderStatus;
use RdKafka\Conf;
use RdKafka\KafkaConsumer;
use RdKafka\Producer;
use RuntimeException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ConsumeOrderStatusCommand extends Command
{
    protected static $defaultName = 'app:consume-order-status';

    public function __construct(
        private OrderRepositoryInterface $orderRepository,
        private Producer $kafkaProducer,
    ) {
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
                        $oldStatus = $order->getStatus();
                        $order->setStatus(status: OrderStatus::from(value: $newStatus));
                        $this->orderRepository->save($order);
                        $output->writeln("Order {$orderId} status updated to {$newStatus}");

                        // Отправляем уведомление, если статус изменился
                        if ($oldStatus !== $order->getStatus()) {
                            $this->sendOrderNotification(order: $order, notificationType: $this->getNotificationType(status: $order->getStatus()));
                        }
                    }
                }
            }
        }

        return Command::SUCCESS;
    }

    private function sendOrderNotification(Order $order, string $notificationType): void
    {
        $topic = $this->kafkaProducer->newTopic(topic_name: 'order_notifications');

        $message = [
            'type' => 'sms',
            'userPhone' => $order->getOrderPhone(),
            'notificationType' => $notificationType,
            'orderNum' => $order->getId()->toString(),
            'orderItems' => array_map(callback: static fn($item) => [
                'name' => $item->getProductName(),
                'cost' => $item->getPriceAtPurchase(),
                'additionalInfo' => null,
            ], array: $order->getItems()->toArray()),
            'deliveryType' => $order->getDeliveryMethod()->value,
            'deliveryAddress' => [
                'kladrId' => null,
                'fullAddress' => null,
            ],
        ];

        $jsonPayload = json_encode(value: $message);
        if ($jsonPayload === false) {
            throw new RuntimeException(message: 'Failed to encode message to JSON');
        }

        $topic->produce(partition: RD_KAFKA_PARTITION_UA, msgflags: 0, payload: $jsonPayload);
        $this->kafkaProducer->flush(timeout_ms: 10000);
    }

    private function getNotificationType(OrderStatus $status): string
    {
        return match ($status) {
            OrderStatus::PAID => 'success_payment',
            OrderStatus::DELIVERED => 'completed',
            default => 'status_updated',
        };
    }
}
