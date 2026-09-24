<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VehicleLocationHistory extends TenantModel
{
    protected $table = 'vehicle_location_history';

    protected $fillable = [
        'vehicle_id',
        'active_trip_id',
        'latitude',
        'longitude',
        'speed',
        'heading',
        'ignition_on',
        'recorded_at',
        'raw_payload',
        'created_by_id',
        'updated_by_id',
    ];

    protected function casts(): array
    {
        return [
            'vehicle_id' => 'integer',
            'active_trip_id' => 'integer',
            'latitude' => 'decimal:7',
            'longitude' => 'decimal:7',
            'speed' => 'decimal:2',
            'heading' => 'decimal:2',
            'ignition_on' => 'boolean',
            'recorded_at' => 'datetime',
            'raw_payload' => 'array',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class, 'vehicle_id');
    }

    public function activeTrip(): BelongsTo
    {
        return $this->belongsTo(ActiveTrip::class, 'active_trip_id');
    }
}
