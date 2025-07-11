<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Setting;
use App\Repositories\Interface\SettingRepositoryInterface;

final readonly class SettingRepository implements SettingRepositoryInterface
{
    public function getSettings(): ?Setting
    {
        return Setting::first();
    }

    public function updateOrCreateSettings(array $data): Setting
    {
        $setting = Setting::first();

        if ($setting) {
            $setting->update($data);
            return $setting;
        }

        return Setting::create($data);
    }
}
