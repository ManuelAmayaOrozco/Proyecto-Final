<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * Clase que representa un comentario de la aplicación.
 */
class Comment extends Model
{

    /**
     * Un Comentario es creado por un Usuario.
     */
    public function user(): HasOne {
        return $this->hasOne(User::class);
    }

    /**
     * Un Comentario aparece en un Post.
     */
    public function post(): HasOne {
        return $this->hasOne(Post::class);
    }

}
