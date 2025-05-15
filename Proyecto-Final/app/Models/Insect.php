<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Clase que representa un insecto cuya información es mostrada en la aplicación.
 */
class Insect extends Model
{
    /**
     * Un Insecto es registrado por un Usuario.
     */
    public function user(): HasOne {
        return $this->hasOne(User::class);
    }

    /**
     * Un Insecto es referenciado en muchos Posts.
     */
    public function post(): HasMany {
        return $this->hasMany(Post::class, 'insect_id', 'id');
    }

    /**
     * Un Insecto tiene una o varias imágenes. 
     */
    public function photos(): HasMany {
        return $this->hasMany(InsectPhoto::class);
    }

}