<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Clase que representa un Post de la aplicación.
 */
class Post extends Model
{

    /**
     * Un Post le pertenece a un Usuario.
     */
    public function user(): HasOne {
        return $this->hasOne(User::class);
    }

    /**
     * Un Post referencia a un Insecto.
     */
    public function insect(): HasOne {
        return $this->hasOne(Insect::class);
    }

    /**
     * Un Post puede tener varios Comentarios.
     */
    public function comments(): HasMany {
        return $this->hasMany(Comment::class, 'post_id', 'id');

    }

    /**
     * Varios Posts pueden tener varias Etiquetas.
     */
    public function tags(): BelongsToMany {
        return $this->belongsToMany(Tag::class);
    }

    /**
     * Varios Posts pueden recibir likes de varios Usuarios. 
     */
    public function likedByUsers(): BelongsToMany {
        return $this->belongsToMany(User::class, 'likes');
    }

}
