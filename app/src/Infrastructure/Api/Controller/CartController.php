<?php

declare(strict_types=1);

namespace App\Infrastructure\Api\Controller;

use App\Application\Command\AddToCartCommand;
use App\Domain\ValueObject\UuidV7;
use App\Infrastructure\Api\DTO\AddToCartResponseDTO;
use App\Infrastructure\Api\Request\AddToCartRequest;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Throwable;

final class CartController extends AbstractController
{
    public function __construct(
        private MessageBusInterface $commandBus,
        private SerializerInterface $serializer,
    ) {}

    #[Route(path: '/api/cart/add', name: 'api_cart_add', methods: ['POST'])]
    #[OA\Post(
        path: '/api/cart/add',
        summary: 'Add product to cart',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: '#/components/schemas/AddToCartRequest'),
        ),
        responses: [
            new OA\Response(
                response: Response::HTTP_OK,
                description: 'Product added to cart',
                content: new OA\JsonContent(ref: '#/components/schemas/AddToCartResponse'),
            ),
            new OA\Response(response: Response::HTTP_INTERNAL_SERVER_ERROR, description: 'Server Error'),
        ],
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
            $responseDTO = new AddToCartResponseDTO(status: 'Product added to cart');
            $json = $this->serializer->serialize($responseDTO, 'json');

            return new JsonResponse(data: $json, status: Response::HTTP_ACCEPTED, json: true);
        } catch (Throwable $e) {
            return new JsonResponse(data: ['error' => $e->getMessage()], status: Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
