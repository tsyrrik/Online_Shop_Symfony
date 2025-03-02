<?php

declare(strict_types=1);

namespace App\Infrastructure\Api\Controller;

use App\Application\Command\AddToCartCommand;
use App\Infrastructure\Api\Request\AddToCartRequest;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;
use Throwable;

class CartController extends AbstractController
{
    public function __construct(private MessageBusInterface $commandBus) {}

    #[Route(
        path: '/api/cart/add',
        name: 'api_cart_add',
        methods: ['POST']
    )]
    public function add(
        #[MapRequestPayload]
        AddToCartRequest $request,
    ): JsonResponse {
        try {
            $command = new AddToCartCommand($request->userId, $request->productId, $request->quantity);
            $this->commandBus->dispatch($command);
        } catch (Throwable $e) {
            return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return new JsonResponse(['status' => 'Product added to cart'], Response::HTTP_ACCEPTED);
    }
}