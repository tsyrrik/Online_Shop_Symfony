<?php

declare(strict_types=1);

namespace App\Infrastructure\Api\Controller;

use App\Application\Command\AddToCartCommand;
use App\Application\Handler\AddToCartHandler;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{
    public function __construct(private AddToCartHandler $addToCartHandler) {}

    /**
     * @Route("/api/cart/add", name="api_cart_add", methods={"POST"})
     */
    public function add(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['userId'], $data['productId'])) {
            return new JsonResponse(['error' => 'Не указаны необходимые параметры'], 400);
        }

        $quantity = $data['quantity'] ?? 1;
        $command  = new AddToCartCommand($data['userId'], $data['productId'], $quantity);

        try {
            $this->addToCartHandler->handle($command);
        } catch (Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], 500);
        }

        return new JsonResponse(['status' => 'Товар добавлен в корзину']);
    }
}
