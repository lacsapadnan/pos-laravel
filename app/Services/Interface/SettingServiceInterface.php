<?php

declare(strict_types=1);

namespace App\Services\Interface;

use App\Models\Setting;

interface SettingServiceInterface
{
    public function getSettings(): ?Setting;

    public function updateSettings(array $data): Setting;
}
