<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DriverRouteAssignment extends TenantModel
{
    protected $table = 'driver_route_assignments';

    protected $fillable = [
        'driver_id',
        'vehicle_id',
        'route_id',
        'assigned_from',
        'assigned_to',
        'is_active',
        'created_by_id',
        'updated_by_id',
    ];

    protected function casts(): array
    {
        return [
            'driver_id' => 'integer',
            'vehicle_id' => 'integer',
            'route_id' => 'integer',
            'assigned_from' => 'date',
            'assigned_to' => 'date',
            'is_active' => 'boolean',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    public function driver(): BelongsTo
    {
        return $this->belongsTo(AdminAndDriver::class, 'driver_id');
    }

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class, 'vehicle_id');
    }

    public function route(): BelongsTo
    {
        return $this->belongsTo(VehicleRoute::class, 'route_id');
    }
}
