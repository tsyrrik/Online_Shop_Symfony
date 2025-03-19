<?php

declare(strict_types=1);

namespace App\Infrastructure\Api\Controller;

use App\Application\Command\RegisterUserCommand;
use App\Infrastructure\Api\Request\RegisterUserRequest;
use Ramsey\Uuid\Uuid;
use RdKafka\Producer;
use RuntimeException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Messenger\MessageBusInterface;

final class RegistrationController extends AbstractController
{
    private MessageBusInterface $commandBus;

    private Producer $kafkaProducer;

    public function __construct(MessageBusInterface $commandBus, Producer $kafkaProducer)
    {
        $this->commandBus = $commandBus;
        $this->kafkaProducer = $kafkaProducer;
    }

    public function register(
        #[MapRequestPayload]
        RegisterUserRequest $registerRequest,
    ): JsonResponse {
        $registerCommand = new RegisterUserCommand(
            name: $registerRequest->name,
            phone: $registerRequest->phone,
            email: $registerRequest->email,
        );
        $this->commandBus->dispatch($registerCommand);

        // Отправка уведомления в Kafka
        $this->sendRegistrationNotification(userPhone: $registerRequest->phone);

        return new JsonResponse(
            data: ['status' => 'User registration initiated'],
            status: Response::HTTP_ACCEPTED,
        );
    }

    private function sendRegistrationNotification(string $userPhone): void
    {
        $topic = $this->kafkaProducer->newTopic(topic_name: 'user_registration');

        $message = [
            'type' => 'sms',
            'userPhone' => $userPhone,
            'promoId' => Uuid::uuid4()->toString(),
        ];
        $jsonPayload = json_encode(value: $message);

        if ($jsonPayload === false) {
            throw new RuntimeException(message: 'Failed to encode message to JSON');
        }

        $topic->produce(partition: RD_KAFKA_PARTITION_UA, msgflags: 0, payload: $jsonPayload);
        $this->kafkaProducer->flush(timeout_ms: 10000);
    }
}
