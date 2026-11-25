<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Deploy extends Model
{
    public $timestamps = false;

    protected $fillable = ['commit', 'message', 'run_url', 'duration_seconds', 'deployed_at'];

    protected $casts = ['deployed_at' => 'datetime'];

    public function shortCommit(): string
    {
        return substr($this->commit, 0, 7);
    }

    public function duration(): ?string
    {
        if ($this->duration_seconds === null) {
            return null;
        }

        return intdiv($this->duration_seconds, 60).'m '.str_pad($this->duration_seconds % 60, 2, '0', STR_PAD_LEFT).'s';
    }
}
