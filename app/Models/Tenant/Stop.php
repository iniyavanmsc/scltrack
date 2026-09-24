<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Stop extends TenantModel
{
    protected $table = 'stops';

    protected $fillable = [
        'route_id',
        'stop_name',
        'stop_code',
        'latitude',
        'longitude',
        'stop_order',
        'is_active',
        'created_by_id',
        'updated_by_id',
    ];

    protected function casts(): array
    {
        return [
            'route_id' => 'integer',
            'latitude' => 'decimal:7',
            'longitude' => 'decimal:7',
            'stop_order' => 'integer',
            'is_active' => 'boolean',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    public function route(): BelongsTo
    {
        return $this->belongsTo(VehicleRoute::class, 'route_id');
    }

    public function studentRouteAssignments(): HasMany
    {
        return $this->hasMany(StudentRouteAssignment::class, 'stop_id');
    }
}
