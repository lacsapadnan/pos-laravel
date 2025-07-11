<?php

namespace App\Services\Interface;

use Illuminate\Support\Collection;

interface NotificationServiceInterface
{
    public function getLowStockNotifications(): Collection;
    public function getNotificationCount(): int;
    public function markAsRead(int $productId): bool;
    public function markAllAsRead(): bool;
    public function getUnreadNotifications(): Collection;
}
