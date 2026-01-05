<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Recipe extends Model
{
    protected $fillable = [
        'title',
        'ingredients',
        'steps',
        'url',
        'category',
        'title_cleaned',
        'total_ingredients',
        'ingredients_cleaned',
        'total_steps',
    ];
}
