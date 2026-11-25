<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;

class Portfolio extends Model
{
    use Sluggable;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'portfolio';

    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['title', 'tags', 'category', 'description', 'date', 'summary', 'image', 'link', 'slug'];

    /**
     * Return the sluggable configuration array for this model.
     */
    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'title',
                // Keep the URL in step with the title; the package keeps slugs unique.
                'onUpdate' => true,
            ]
        ];
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
