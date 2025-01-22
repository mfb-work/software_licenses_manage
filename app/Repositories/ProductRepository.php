<?php

namespace App\Repositories;

use App\Models\Product;

class ProductRepository
{
    public function findByEnvatoItemId(string $envatoItemId)
    {
        return Product::where('envato_item_id', $envatoItemId)->first();
    }

    public function create(array $data)
    {
        return Product::create($data);
    }
}
