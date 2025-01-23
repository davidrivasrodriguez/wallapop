<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting;

class SettingSeeder extends Seeder
{
    public function run()
    {
        Setting::create([
            'name' => 'max_images',
            'maxImages' => 5
        ]);
    }
}