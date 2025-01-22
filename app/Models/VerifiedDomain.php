<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VerifiedDomain extends Model
{
    protected $fillable = ['license_id', 'domain'];

    public function license()
    {
        return $this->belongsTo(License::class);
    }
}
