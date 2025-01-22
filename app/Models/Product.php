<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['envato_item_id', 'name'];

    public function licenses()
    {
        return $this->hasMany(License::class);
    }
}
