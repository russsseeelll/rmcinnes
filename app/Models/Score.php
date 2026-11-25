<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Score extends Model
{
    protected $fillable = ['name', 'organisation', 'score'];

    public static function top(int $limit = 10)
    {
        return static::orderByDesc('score')->orderBy('created_at')->limit($limit)->get(['id', 'name', 'organisation', 'score']);
    }
}
