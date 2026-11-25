<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SkillGroup extends Model
{
    protected $fillable = ['name', 'skills', 'sort_order'];

    public function skillList(): array
    {
        return array_values(array_filter(array_map('trim', explode(',', $this->skills))));
    }
}
