<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;

abstract class TenantAuthenticatable extends Authenticatable
{
    use HasFactory;

    protected $connection = 'tenant';

    protected $guarded = [
        'id',
    ];

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(AdminAndDriver::class, 'created_by_id');
    }

    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(AdminAndDriver::class, 'updated_by_id');
    }
}
