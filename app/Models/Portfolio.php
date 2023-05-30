<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Portfolio extends Model
{
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
    protected $fillable = ['title', 'tags', 'category', 'description', 'date', 'summary', 'image', 'link'];

}
