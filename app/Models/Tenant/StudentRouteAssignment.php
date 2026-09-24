<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentRouteAssignment extends TenantModel
{
    protected $table = 'student_route_assignments';

    protected $fillable = [
        'student_id',
        'route_id',
        'stop_id',
        'assigned_from',
        'assigned_to',
        'is_active',
        'created_by_id',
        'updated_by_id',
    ];

    protected function casts(): array
    {
        return [
            'student_id' => 'integer',
            'route_id' => 'integer',
            'stop_id' => 'integer',
            'assigned_from' => 'date',
            'assigned_to' => 'date',
            'is_active' => 'boolean',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    public function route(): BelongsTo
    {
        return $this->belongsTo(VehicleRoute::class, 'route_id');
    }

    public function stop(): BelongsTo
    {
        return $this->belongsTo(Stop::class, 'stop_id');
    }
}
