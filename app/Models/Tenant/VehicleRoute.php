<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Relations\HasMany;

class VehicleRoute extends TenantModel
{
    protected $table = 'vehicle_routes';

    protected $fillable = [
        'route_name',
        'route_code',
        'trip_type',
        'start_location',
        'end_location',
        'is_active',
        'created_by_id',
        'updated_by_id',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    public function stops(): HasMany
    {
        return $this->hasMany(Stop::class, 'route_id');
    }

    public function driverRouteAssignments(): HasMany
    {
        return $this->hasMany(DriverRouteAssignment::class, 'route_id');
    }

    public function activeTrips(): HasMany
    {
        return $this->hasMany(ActiveTrip::class, 'route_id');
    }

    public function studentRouteAssignments(): HasMany
    {
        return $this->hasMany(StudentRouteAssignment::class, 'route_id');
    }

    public function getDisplayNameAttribute(): string
    {
        return collect([$this->route_name, $this->route_code, $this->trip_type])
            ->filter()
            ->implode(' - ');
    }
}
