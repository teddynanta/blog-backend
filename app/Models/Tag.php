<?php

namespace App\Models;

use Stringable;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Tag extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'slug'
    ];
    public static function boot()
    {
        parent::boot();
        static::creating(function ($tag) {
            $tag->slug = Str::slug($tag->name);
        });
    }
}
