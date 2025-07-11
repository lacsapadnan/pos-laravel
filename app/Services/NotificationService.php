<?php

namespace App\Services;

use App\Services\Interface\NotificationServiceInterface;
use App\Services\Interface\StockServiceInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

final class NotificationService implements NotificationServiceInterface
{
    protected StockServiceInterface $stockService;

    public function __construct(StockServiceInterface $stockService)
    {
        $this->stockService = $stockService;
    }

    public function getLowStockNotifications(): Collection
    {
        return Cache::remember('low_stock_notifications', 300, function () {
            $lowStockProducts = $this->stockService->getLowStockProducts();

            return $lowStockProducts->map(function ($product) {
                return [
                    'id' => $product->id,
                    'type' => 'low_stock',
                    'title' => 'Stok Rendah: ' . $product->name,
                    'message' => "Stok {$product->name} ({$product->code}) tersisa {$product->stock} {$product->unit->name}. Minimum stok: {$product->minimum_stock}",
                    'product' => $product,
                    'created_at' => now(),
                    'is_read' => $this->isNotificationRead($product->id),
                    'priority' => $this->getNotificationPriority($product),
                    'url' => route('product.show', $product->id),
                ];
            })->sortByDesc('priority');
        });
    }

    public function getNotificationCount(): int
    {
        return $this->getUnreadNotifications()->count();
    }

    public function markAsRead(int $productId): bool
    {
        $readNotifications = $this->getReadNotifications();
        $readNotifications[] = $productId;

        Cache::put('read_notifications_' . auth()->id(), array_unique($readNotifications), 86400);
        $this->clearNotificationCache();

        return true;
    }

    public function markAllAsRead(): bool
    {
        $allNotifications = $this->getLowStockNotifications();
        $productIds = $allNotifications->pluck('id')->toArray();

        Cache::put('read_notifications_' . auth()->id(), $productIds, 86400);
        $this->clearNotificationCache();

        return true;
    }

    public function getUnreadNotifications(): Collection
    {
        return $this->getLowStockNotifications()->filter(function ($notification) {
            return !$notification['is_read'];
        });
    }

    protected function isNotificationRead(int $productId): bool
    {
        $readNotifications = $this->getReadNotifications();
        return in_array($productId, $readNotifications);
    }

    protected function getReadNotifications(): array
    {
        return Cache::get('read_notifications_' . auth()->id(), []);
    }

    protected function getNotificationPriority($product): int
    {
        if ($product->stock <= 0) {
            return 3; // Critical - out of stock
        } elseif ($product->stock <= ($product->minimum_stock * 0.5)) {
            return 2; // High - very low stock
        } else {
            return 1; // Medium - low stock
        }
    }

    protected function clearNotificationCache(): void
    {
        Cache::forget('low_stock_notifications');
    }

    public function getPriorityLabel(int $priority): string
    {
        return match ($priority) {
            3 => 'Kritis',
            2 => 'Tinggi',
            1 => 'Sedang',
            default => 'Rendah',
        };
    }

    public function getPriorityColor(int $priority): string
    {
        return match ($priority) {
            3 => 'bg-red',
            2 => 'bg-orange',
            1 => 'bg-yellow',
            default => 'bg-blue',
        };
    }
}
