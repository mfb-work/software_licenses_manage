<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EnvatoApiSetting extends Model
{
    protected $table = 'envato_api_settings';

    protected $fillable = ['token'];
}
