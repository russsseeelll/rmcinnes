<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StravaConnection extends Model
{
    protected $fillable = ['client_id', 'client_secret', 'refresh_token'];

    protected $hidden = ['client_secret', 'refresh_token'];

    protected $casts = ['client_secret' => 'encrypted', 'refresh_token' => 'encrypted'];

    public static function current(): self
    {
        return static::query()->first() ?? new static;
    }
}
