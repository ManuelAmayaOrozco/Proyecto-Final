<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Post extends Model
{
    public function user(): HasOne {
        return $this->hasOne(User::class);
    }

    public function insect(): HasOne {
        return $this->hasOne(Insect::class);
    }

    public function comments(): HasMany {
        return $this->hasMany(Comment::class, 'post_id', 'id');

    }
}
