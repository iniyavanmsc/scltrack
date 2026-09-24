<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Relations\HasMany;

class ClassSection extends TenantModel
{
    protected $table = 'class_sections';

    protected $fillable = [
        'class_name',
        'section',
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

    public function students(): HasMany
    {
        return $this->hasMany(Student::class, 'class_section_id');
    }

    public function getDisplayNameAttribute(): string
    {
        return $this->class_name . ($this->section ? ' - ' . $this->section : '');
    }
}
