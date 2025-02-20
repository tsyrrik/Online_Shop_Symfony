<?php

namespace App\Infrastructure\Api\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AdminOrderController extends AbstractController
{
    /**
     * @Route("/admin/orders/{orderId}/status", name="admin_order_update_status", methods={"POST"})
     */
    public function updateStatus(Request $request, int $orderId): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['status'])) {
            return new JsonResponse(['error' => 'Missing required parameter: status'], 400);
        }

        $status = $data['status'];

        $allowedStatuses = ['pending', 'processing', 'completed', 'cancelled'];
        if (!in_array($status, $allowedStatuses, true)) {
            return new JsonResponse(['error' => 'Invalid status provided'], 400);
        }

        try {
            return new JsonResponse(['status' => 'Order updated successfully'], 200);
        } catch (\Exception $e) {

            return new JsonResponse(['error' => 'Failed to update order status'], 500);
        }
    }
}
