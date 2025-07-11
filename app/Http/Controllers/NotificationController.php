<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Services\Interface\NotificationServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class NotificationController extends Controller
{
    protected NotificationServiceInterface $notificationService;

    public function __construct(NotificationServiceInterface $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    /**
     * Mark a single notification as read
     */
    public function markAsRead(Request $request): JsonResponse
    {
        $request->validate([
            'product_id' => 'required|integer|exists:products,id'
        ]);

        $success = $this->notificationService->markAsRead($request->product_id);

        if ($success) {
            $newCount = $this->notificationService->getNotificationCount();

            return response()->json([
                'success' => true,
                'message' => 'Notifikasi berhasil ditandai sebagai dibaca',
                'notification_count' => $newCount
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Gagal menandai notifikasi sebagai dibaca'
        ], 500);
    }

    /**
     * Mark all notifications as read
     */
    public function markAllAsRead(): JsonResponse
    {
        $success = $this->notificationService->markAllAsRead();

        if ($success) {
            return response()->json([
                'success' => true,
                'message' => 'Semua notifikasi berhasil ditandai sebagai dibaca',
                'notification_count' => 0
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Gagal menandai semua notifikasi sebagai dibaca'
        ], 500);
    }

    /**
     * Get notification count for AJAX requests
     */
    public function getCount(): JsonResponse
    {
        $count = $this->notificationService->getNotificationCount();

        return response()->json([
            'notification_count' => $count
        ]);
    }
}
