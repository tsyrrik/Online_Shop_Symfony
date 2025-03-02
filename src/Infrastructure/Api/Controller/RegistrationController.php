<?php

declare(strict_types=1);

namespace App\Infrastructure\Api\Controller;

use App\Application\Command\RegisterUserCommand;
use App\Infrastructure\Api\Request\RegisterUserRequest;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Messenger\MessageBusInterface;

class RegistrationController extends AbstractController
{
    public function __construct(private MessageBusInterface $commandBus) {}

    public function register(
        #[MapRequestPayload]
        RegisterUserRequest $request,
    ): JsonResponse {
        $command = new RegisterUserCommand(
            name: $request->name,
            phone: $request->email,
            email: $request->phone,
        );
        $this->commandBus->dispatch($command);

        return new JsonResponse(data: ['status' => 'User registration initiated'], status: Response::HTTP_ACCEPTED);
    }
}
