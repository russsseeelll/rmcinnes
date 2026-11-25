<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Profile extends Model
{
    protected $fillable = [
        'name', 'headline', 'location', 'email', 'github_url', 'linkedin_url',
        'intro', 'contact_heading', 'cv_path', 'meta_description',
    ];

    /** The site owner. Falls back to an empty profile so a fresh install still renders. */
    public static function current(): self
    {
        return once(fn () => static::query()->first() ?? new static([
            'name' => 'Your Name',
            'headline' => 'Run php artisan db:seed',
            'email' => config('mail.from.address'),
            'intro' => 'Add your details in database/seeders/ProfileSeeder.php.',
        ]));
    }

    /** "Your Name" -> "Y. Name", for the small name badge. */
    public function shortName(): string
    {
        $parts = preg_split('/\s+/', trim($this->name));

        return count($parts) > 1 ? mb_substr($parts[0], 0, 1).'. '.end($parts) : $this->name;
    }

    public function cvUrl(): ?string
    {
        return $this->cv_path ? asset($this->cv_path) : null;
    }

    public function cvDownloadName(): string
    {
        return Str::slug($this->name).'-cv.pdf';
    }
}
