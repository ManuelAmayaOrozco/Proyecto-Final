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

    public function deleteCompletely() {
        // ELIMINAMOS LOS COMENTARIOS
        $this->comments()->delete();

        // ELIMINAMOS FOTO DE IMGBB SI HAY DELETE_URL
        if (!empty($this->photo) && filter_var($this->photo, FILTER_VALIDATE_URL)) {
            if (!empty($this->photo_delete_url)) {
                try {
                    Http::get($this->photo_delete_url);
                } catch (\Exception $e) {
                    \Log::error('Error eliminando imagen de ImgBB: ' . $e->getMessage());
                }
            }
        } else {
            // Si es una foto local
            if (Storage::disk('public')->exists($this->photo)) {
                Storage::disk('public')->delete($this->photo);
            }
        }

        // OBTENEMOS Y ELIMINAMOS LAS ETIQUETAS ÚNICAS
        $uniqueTags = TagController::getAllUniqueTags($this->id);
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
