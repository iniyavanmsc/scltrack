<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Student extends TenantModel
{
    protected $table = 'students';

    protected $fillable = [
        'admission_no',
        'full_name',
        'parent_id',
        'class_section_id',
        'class_name',
        'section',
        'pickup_address',
        'drop_address',
        'is_active',
        'created_by_id',
        'updated_by_id',
    ];

    protected function casts(): array
    {
        return [
            'parent_id' => 'integer',
            'class_section_id' => 'integer',
            'is_active' => 'boolean',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(ParentModel::class, 'parent_id');
    }

    public function classSection(): BelongsTo
    {
        return $this->belongsTo(ClassSection::class, 'class_section_id');
    }

    public function routeAssignments(): HasMany
    {
        return $this->hasMany(StudentRouteAssignment::class, 'student_id');
    }
}
