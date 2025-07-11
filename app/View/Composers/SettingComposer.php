<?php

declare(strict_types=1);

namespace App\View\Composers;

use App\Services\Interface\SettingServiceInterface;
use Illuminate\View\View;

final class SettingComposer
{
    public function __construct(
        private readonly SettingServiceInterface $settingService
    ) {}

    public function compose(View $view): void
    {
        $setting = $this->settingService->getSettings();

        $view->with('websiteSetting', $setting);
    }
}
