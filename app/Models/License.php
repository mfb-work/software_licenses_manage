<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class License extends Model
{
    protected $fillable = [
        'product_id',
        'license_key',
        'purchase_code',
        'buyer_username',
        'buyer_email',
        'domain',
        'activated',
        'expires_at'
    ];

    protected $casts = [
        'activated' => 'boolean',
        'expires_at' => 'datetime',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function verifiedDomains()
    {
        return $this->hasMany(VerifiedDomain::class);
    }
}
