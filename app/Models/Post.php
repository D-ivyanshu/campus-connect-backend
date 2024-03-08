<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function user() {
        return $this->belongsTo(User::class);
    }

    // if the parent_id is null then it means the comment is done on the comment 
    public function comments()
    {
        return $this->hasMany(Comment::class)->whereNull('parent_id');
    }

}
