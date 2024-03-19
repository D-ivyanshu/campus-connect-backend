<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Stevebauman\Purify\Casts\PurifyHtmlOnGet;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Post extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function scopeLatest($query)
    {
        return $query->orderBy('created_at', 'desc');
    }

    public function user() {
        return $this->belongsTo(User::class);
    }

    // if the parent_id is null then it means the comment is done on the comment 
    public function comments()
    {
        return $this->hasMany(Comment::class)->whereNull('parent_id');
    }

    protected $casts = [
        'body' => PurifyHtmlOnGet::class,
    ];

}
