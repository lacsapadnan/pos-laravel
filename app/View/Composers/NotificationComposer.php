<?php

namespace App\View\Composers;

use App\Services\Interface\NotificationServiceInterface;
use Illuminate\View\View;

final class NotificationComposer
{
    protected NotificationServiceInterface $notificationService;

    public function __construct(NotificationServiceInterface $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    public function compose(View $view): void
    {
        if (auth()->check()) {
            $notifications = $this->notificationService->getUnreadNotifications();
            $notificationCount = $notifications->count();

            $view->with([
                'notifications' => $notifications,
                'notificationCount' => $notificationCount,
                'notificationService' => $this->notificationService,
            ]);
        } else {
            $view->with([
                'notifications' => collect(),
                'notificationCount' => 0,
                'notificationService' => $this->notificationService,
            ]);
        }
    }
}
