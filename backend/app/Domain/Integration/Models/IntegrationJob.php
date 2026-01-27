<?php

namespace App\Domain\Integration\Models;

use Illuminate\Database\Eloquent\Model;

class IntegrationJob extends Model
{
    protected $table = 'integration_jobs';

    protected $fillable = [
        'external_id',
        'payload',
        'status',
        'attempts',
        'last_error',
        'processed_at'
    ];

    protected $casts = [
        'payload' => 'array',
        'processed_at' => 'datetime',
    ];
}
