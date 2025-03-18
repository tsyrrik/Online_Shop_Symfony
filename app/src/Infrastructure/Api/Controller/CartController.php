<?php

declare(strict_types=1);

namespace App\Infrastructure\Api\Controller;

use App\Application\Command\AddToCartCommand;
use App\Domain\ValueObject\UuidV7;
use App\Infrastructure\Api\Request\AddToCartRequest;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;
use Throwable;

final class CartController extends AbstractController
{
    public function __construct(private MessageBusInterface $commandBus) {}

    #[Route(
        path: '/api/cart/add',
        name: 'api_cart_add',
        methods: ['POST'],
    )]
    public function add(
        #[MapRequestPayload]
        AddToCartRequest $request,
    ): JsonResponse {
        try {
            $command = new AddToCartCommand(
                userId: new UuidV7(uuid: $request->userId),
                productId: new UuidV7(uuid: $request->productId),
                quantity: $request->quantity,
            );
            $this->commandBus->dispatch($command);
        } catch (Throwable $e) {
            return new JsonResponse(data: ['error' => $e->getMessage()], status: Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return new JsonResponse(data: ['status' => 'Product added to cart'], status: Response::HTTP_ACCEPTED);
    }
}
