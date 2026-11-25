<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HealthCheck extends Model
{
    public $timestamps = false;

    protected $fillable = ['ok', 'status_code', 'response_ms', 'checked_at'];

    protected $casts = ['ok' => 'boolean', 'checked_at' => 'datetime'];
}
