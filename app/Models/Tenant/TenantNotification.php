<?php

namespace App\Models\Tenant;

class TenantNotification extends TenantModel
{
    protected $table = 'notifications';

    protected $fillable = [
        'title',
        'message',
        'notification_type',
        'channel',
        'is_read',
        'read_at',
        'sent_at',
        'payload',
        'created_by_id',
        'updated_by_id',
    ];

    protected function casts(): array
    {
        return [
            'is_read' => 'boolean',
            'read_at' => 'datetime',
            'sent_at' => 'datetime',
            'payload' => 'array',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }
}
