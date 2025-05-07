<?php

namespace App\Models;

use Stancl\Tenancy\Database\Models\Tenant as BaseTenant;
use Stancl\Tenancy\Contracts\TenantWithDatabase;
use Stancl\Tenancy\Database\Concerns\HasDatabase;
use Stancl\Tenancy\Database\Concerns\HasDomains;

class Tenant extends BaseTenant implements TenantWithDatabase
{
    use HasDatabase, HasDomains;

    protected $fillable = [
        'id',
        'name',
        'email',
        'organization',
        'domain',
        'address',
        'plan',
        'status',
        'data',
        'customize_data', // ✅ Add customize_data to fillable
    ];

    protected $casts = [
        'data' => 'array',
        'customize_data' => 'array', // ✅ Correct cast
    ];

    public static function getCustomColumns(): array
    {
        return [
            'id',
            'name',
            'email',
            'organization',
            'domain',
            'address',
            'plan',
            'status',
            'data',
            'customize_data', // ✅ Add customize_data to custom columns if needed
        ];
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
