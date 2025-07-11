<?php

declare(strict_types=1);

namespace App\Repositories\Interface;

use App\Models\Setting;

interface SettingRepositoryInterface
{
    public function getSettings(): ?Setting;

    public function updateOrCreateSettings(array $data): Setting;
}
