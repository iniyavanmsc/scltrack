<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ActiveTrip extends TenantModel
{
    protected $table = 'active_trips';

    protected $fillable = [
        'route_id',
        'vehicle_id',
        'driver_id',
        'trip_date',
        'trip_status',
        'started_at',
        'ended_at',
        'notes',
        'created_by_id',
        'updated_by_id',
    ];

    protected function casts(): array
    {
        return [
            'route_id' => 'integer',
            'vehicle_id' => 'integer',
            'driver_id' => 'integer',
            'trip_date' => 'date',
            'started_at' => 'datetime',
            'ended_at' => 'datetime',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    public function route(): BelongsTo
    {
        return $this->belongsTo(VehicleRoute::class, 'route_id');
    }

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class, 'vehicle_id');
    }

    public function driver(): BelongsTo
    {
        return $this->belongsTo(AdminAndDriver::class, 'driver_id');
    }

    public function liveVehicleLocation(): HasOne
    {
        return $this->hasOne(LiveVehicleLocation::class, 'active_trip_id');
    }

    public function vehicleLocationHistory(): HasMany
    {
        return $this->hasMany(VehicleLocationHistory::class, 'active_trip_id');
    }

    public function getDisplayNameAttribute(): string
    {
        return collect([
            $this->trip_date ? $this->trip_date->format('d-m-Y') : null,
            $this->route?->display_name,
            $this->vehicle?->vehicle_number,
            $this->driver?->full_name,
        ])->filter()->implode(' - ');
    }
}
