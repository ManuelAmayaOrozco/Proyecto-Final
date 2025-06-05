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
use Illuminate\Support\Facades\Log;

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
        Log::info("Inicio deleteCompletely para post ID {$this->id}");

        try {
            Log::info("Eliminando comentarios");
            $this->comments()->delete();

            Log::info("Obteniendo etiquetas únicas");
            $uniqueTags = collect(TagController::getAllUniqueTags($this->id))->filter()->values();
            Log::info("Etiquetas únicas obtenidas: " . $uniqueTags->implode(','));

            Log::info("Eliminando de post_tag");
            DB::table('post_tag')->where('post_id', $this->id)->delete();

            if ($uniqueTags->isNotEmpty()) {
                Log::info("Eliminando etiquetas");
                Tag::whereIn('id', $uniqueTags)->delete();
            }

            Log::info("Eliminando favoritos");
            DB::table('favorites')->where('post_id', $this->id)->delete();

            Log::info("Eliminando post");
            $this->delete();

            Log::info("Post eliminado correctamente");

        } catch (\Exception $e) {
            Log::error("Error al eliminar post ID {$this->id}: " . $e->getMessage());
            throw $e;
        }
    }

}
