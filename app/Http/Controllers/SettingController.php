<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\StoreSettingRequest;
use App\Services\Interface\SettingServiceInterface;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

final class SettingController extends Controller
{
    public function __construct(
        private readonly SettingServiceInterface $settingService
    ) {}

    /**
     * Display the settings page.
     */
    public function index(): View
    {
        $setting = $this->settingService->getSettings();

        return view('pages.setting.index', compact('setting'));
    }

    /**
     * Update the settings.
     */
    public function update(StoreSettingRequest $request): RedirectResponse
    {
        try {
            $this->settingService->updateSettings($request->validated());

            return redirect()
                ->route('settings.index')
                ->with('success', 'Pengaturan berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat memperbarui pengaturan.');
        }
    }
}
