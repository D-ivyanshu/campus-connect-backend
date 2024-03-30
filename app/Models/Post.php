<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Stevebauman\Purify\Casts\PurifyHtmlOnGet;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Post extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function scopeLatest($query)
    {
        return $query->orderBy('created_at', 'desc');
    }

    public function user() : BelongsTo {
        return $this->belongsTo(User::class);
    }

    // if the parent_id is null then it means the comment is done on the comment 
    public function comments() : HasMany
    {
        return $this->hasMany(Comment::class)->whereNull('parent_id');
    }

    public function media(): MorphMany
    {
        return $this->morphMany(Media::class, 'medially');
    }

    public function reactions(): MorphMany 
    {
        return $this->morphMany(Reaction::class, 'object');
    }

    protected $casts = [
        'body' => PurifyHtmlOnGet::class,
    ];

}
