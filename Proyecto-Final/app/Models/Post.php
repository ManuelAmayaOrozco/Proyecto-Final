<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\TagController;
use Illuminate\Support\Facades\DB;
use App\Models\Tag;
use Illuminate\Support\Facades\Http;

/**
 * Clase que representa un Post de la aplicación.
 */
class Post extends Model
{

    /**
     * Un Post le pertenece a un Usuario.
     */
    public function user() {
        return $this->belongsTo(User::class, 'belongs_to', 'id');
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

    public function deleteCompletely() {
        // ELIMINAMOS LOS COMENTARIOS
        $this->comments()->delete();

        // OBTENEMOS Y ELIMINAMOS LAS ETIQUETAS ÚNICAS
        $uniqueTags = collect(TagController::getAllUniqueTags($this->id))->filter()->values();
        DB::table('post_tag')->where('post_id', $this->id)->delete();

        if ($uniqueTags && $uniqueTags->isNotEmpty()) {
            Tag::whereIn('id', $uniqueTags)->delete();
        }

        // ELIMINAMOS LOS FAVORITOS RELACIONADOS
        DB::table('favorites')->where('post_id', $this->id)->delete();

        // ELIMINAMOS EL POST
        $this->delete();
    }

}
