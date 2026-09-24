<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Relations\HasMany;

class AdminAndDriver extends TenantAuthenticatable
{
    protected $table = 'admin_and_drivers';

    protected $fillable = [
        'full_name',
        'phone',
        'email_id',
        'username',
        'password',
        'user_role',
        'license_number',
        'license_expiry_date',
        'address',
        'is_active',
        'created_by_id',
        'updated_by_id',
    ];

    protected $hidden = [
        'password',
    ];

    protected function casts(): array
    {
        return [
            'license_expiry_date' => 'date',
            'is_active' => 'boolean',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    public function driverRouteAssignments(): HasMany
    {
        return $this->hasMany(DriverRouteAssignment::class, 'driver_id');
    }

    public function activeTrips(): HasMany
    {
        return $this->hasMany(ActiveTrip::class, 'driver_id');
    }
}
