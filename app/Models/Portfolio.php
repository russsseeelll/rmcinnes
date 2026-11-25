<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
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
                'onUpdate' => true,
            ]
        ];
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function tagList(): array
    {
        return array_values(array_filter(array_map('trim', explode(',', (string) $this->tags))));
    }

    public function scopeFiltered(Builder $query, ?string $search = null, ?string $category = null): Builder
    {
        return $query
            ->when($search, fn ($q) => $q->where(fn ($q) => $q
                ->where('title', 'LIKE', "%{$search}%")
                ->orWhere('tags', 'LIKE', "%{$search}%")
                ->orWhere('category', 'LIKE', "%{$search}%")))
            ->when($category, fn ($q) => $q->where('category', $category))
            ->orderByDesc('date');
    }
}
