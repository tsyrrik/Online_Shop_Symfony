<?php

declare(strict_types=1);

namespace App\Infrastructure\Api\Controller;

use App\Application\Command\RegisterUserCommand;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Messenger\MessageBusInterface;

class RegistrationController extends AbstractController
{
    public function __construct(private MessageBusInterface $commandBus) {}

    public function register(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        // Валидация данных
        $command = new RegisterUserCommand($data['name'], $data['email'], $data['phone']);
        $this->commandBus->dispatch($command);

        return new JsonResponse(['status' => 'User registration initiated'], 202);
    }
}
