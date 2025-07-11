<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Setting;
use App\Repositories\Interface\SettingRepositoryInterface;
use App\Services\Interface\SettingServiceInterface;
use Illuminate\Support\Facades\Storage;

final readonly class SettingService implements SettingServiceInterface
{
    public function __construct(
        private SettingRepositoryInterface $settingRepository
    ) {}

    public function getSettings(): ?Setting
    {
        return $this->settingRepository->getSettings();
    }

    public function updateSettings(array $data): Setting
    {
        // Handle logo upload if present
        if (isset($data['logo']) && $data['logo'] instanceof \Illuminate\Http\UploadedFile) {
            $logoPath = $data['logo']->store('logos', 'public');
            $data['logo'] = $logoPath;
        }

        return $this->settingRepository->updateOrCreateSettings($data);
    }
}
