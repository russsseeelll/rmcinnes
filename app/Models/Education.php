<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Education extends Model
{
    protected $table = 'education';

    protected $fillable = ['qualification', 'institution', 'period', 'grade', 'in_progress', 'featured', 'sort_order'];

    protected $casts = ['in_progress' => 'boolean', 'featured' => 'boolean'];
}
