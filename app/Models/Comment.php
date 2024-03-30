<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Comment extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function scopeLatest($query)
    {
        return $query->orderBy('created_at', 'desc');
    }

    public function user()
    {   
        return $this->belongsTo(User::class);
    }

    public function reactions(): MorphMany 
    {
        return $this->morphMany(Reaction::class, 'object');
    }

}
