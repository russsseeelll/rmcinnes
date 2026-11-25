<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Experience extends Model
{
    protected $fillable = ['title', 'organisation', 'started_on', 'ended_on', 'highlights'];

    protected $casts = ['started_on' => 'date', 'ended_on' => 'date'];

    public function period(): string
    {
        return $this->started_on->format('M Y').' – '.($this->ended_on?->format('M Y') ?? 'now');
    }

    /** @return string[] */
    public function highlightList(): array
    {
        return array_values(array_filter(array_map('trim', explode("\n", (string) $this->highlights))));
    }
}
