<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Profile extends Model
{
    protected $fillable = [
        'name', 'headline', 'location', 'email', 'github_url', 'linkedin_url',
        'intro', 'ai_summary', 'contact_heading', 'meta_description', 'show_mascot',
    ];

    protected $casts = ['show_mascot' => 'boolean'];

    public static function current(): self
    {
        $attributes = request()->attributes;
        if (! $attributes->has('profile')) {
            $attributes->set('profile', static::query()->first() ?? new static([
                'name' => 'Your Name',
                'headline' => 'Run php artisan db:seed',
                'email' => config('mail.from.address'),
                'intro' => 'Add your details in database/seeders/ProfileSeeder.php.',
            ]));
        }

        return $attributes->get('profile');
    }

    public function shortName(): string
    {
        $parts = preg_split('/\s+/', trim($this->name));

        return count($parts) > 1 ? mb_substr($parts[0], 0, 1).'. '.end($parts) : $this->name;
    }
}
