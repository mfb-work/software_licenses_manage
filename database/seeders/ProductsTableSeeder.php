<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductsTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('products')->insert([
            'envato_item_id' => '12345678', // Example Envato Item ID
            'name'           => 'Product 1',
            'created_at'     => now(),
            'updated_at'     => now(),
        ]);
    }
}
