<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Insect extends Model
{
    public function user(): HasOne {
        return $this->hasOne(User::class);
    }

    public function post(): HasMany {
        return $this->hasMany(Post::class, 'insect_id', 'id');
    }

}