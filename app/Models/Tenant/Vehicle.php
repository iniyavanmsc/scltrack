<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Vehicle extends TenantModel
{
    protected $table = 'vehicles';

    protected $fillable = [
        'vehicle_number',
        'registration_number',
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

    public function driverRouteAssignments(): HasMany
    {
        return $this->hasMany(DriverRouteAssignment::class, 'vehicle_id');
    }

    public function activeTrips(): HasMany
    {
        return $this->hasMany(ActiveTrip::class, 'vehicle_id');
    }

    public function liveVehicleLocation(): HasOne
    {
        return $this->hasOne(LiveVehicleLocation::class, 'vehicle_id');
    }

    public function vehicleLocationHistory(): HasMany
    {
        return $this->hasMany(VehicleLocationHistory::class, 'vehicle_id');
    }

}
