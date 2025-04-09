<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;


class Comment extends Model
{
    public function user(): HasOne {
        return $this->hasOne(User::class);
    }

    public function post(): HasOne {
        return $this->hasOne(Post::class);
    }

}
