<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TenantRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'username',
        'organization',
        'domain',
        'address',
        'email',
        'plan',
        'status',
        'data',
    ];

    protected $casts = [
        'data' => 'array',
    ];
}

