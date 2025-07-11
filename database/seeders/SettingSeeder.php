<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

final class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Setting::create([
            'website_name' => 'Laravel POS',
            'address' => 'Jl. Contoh Alamat No. 123, Jakarta',
            'email' => 'admin@laravelpos.com',
            'phone' => '081234567890',
            'logo' => null,
        ]);
    }
}
