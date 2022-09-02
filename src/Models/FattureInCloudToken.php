<?php

namespace U2y\FattureInCloud\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FattureInCloudToken extends Model
{
    use HasFactory;

    protected $fillable = [
        'access_token',
        'refresh_token',
        'expire_at',
    ];

    protected $casts = [
        'expire_at' => 'datetime'
    ];
}
