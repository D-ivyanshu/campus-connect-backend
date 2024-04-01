<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Reaction extends Model
{
    use HasFactory;

    protected $fillable = ['object_id', 'object_type', 'user_id', 'type'];

    public function object(): MorphTo 
    {
        return $this->morphTo();
    }
}
 