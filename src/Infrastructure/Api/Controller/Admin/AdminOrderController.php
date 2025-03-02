<?php

declare(strict_types=1);

namespace App\Infrastructure\Api\Controller\Admin;

use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

use function in_array;

class AdminOrderController extends AbstractController
{
    #[Route(
        path: '/admin/orders/{orderId}/status',
        name: 'admin_order_update_status',
        methods: ['POST'],
    )]
    public function updateStatus(Request $request, int $orderId): JsonResponse
    {
        $data = json_decode(json: $request->getContent(), associative: true);

        if (!isset($data['status'])) {
            return new JsonResponse(data: ['error' => 'Missing required parameter: status'], status: 400);
        }

        $status = $data['status'];

        $allowedStatuses = ['pending', 'processing', 'completed', 'cancelled'];
        if (!in_array(needle: $status, haystack: $allowedStatuses, strict: true)) {
            return new JsonResponse(data: ['error' => 'Invalid status provided'], status: 400);
        }

        try {
            return new JsonResponse(data: ['status' => 'Order updated successfully'], status: 200);
        } catch (Exception $e) {

            return new JsonResponse(data: ['error' => 'Failed to update order status'], status: 500);
        }
    }
}
