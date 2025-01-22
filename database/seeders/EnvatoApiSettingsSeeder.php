<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EnvatoApiSettingsSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('envato_api_settings')->insert([
            'token'      => '123456789',  // Replace with actual token
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
