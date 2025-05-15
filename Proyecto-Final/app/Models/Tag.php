<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Clase que representa una etiqueta en la aplicación, utilizada para búsqueda de Posts.
 */
class Tag extends Model
{

    /**
     * Varias Etiquetas pueden pertenecer a varios Posts.
     */
    public function posts(): BelongsToMany
    {
        return $this->belongsToMany(Post::class);
    }
}
