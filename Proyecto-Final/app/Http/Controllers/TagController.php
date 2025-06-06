<?php

namespace App\Http\Controllers;

use App\Enums\Status;
use App\Models\Post;
use App\Models\Tag;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

/**
 * Controlador para la clase Tag
 */
class TagController extends Controller
{

    /**
     * Función que se encarga de registrar una etiqueta en la base de datos.
     * 
     * @param string $newTag El nombre de la nueva etiqueta.
     */
    public static function registerTag($newTag) {

        $exists = false;

        $tags = DB::table('tags')->get();

        foreach ($tags as $tag) {

            if ($tag->name == $newTag) {

                $exists = true;

            }

        }

        if (!$exists) {

            $createTag = new Tag();
            $createTag->name = $newTag;
            $createTag->save();

        }

    }

    /**
     * Función que obtiene el ID de una etiqueta en específico.
     * 
     * @param string $newTag El nombre de la etiqueta.
     * @return long El ID de la etiqueta.
     */
    public static function getTag($newTag) {

        $tagId = DB::table('tags')
                    ->where('name', '=', $newTag)
                    ->value('id');

        return $tagId;

    }

    /**
     * Función que obtiene todas las etiquetas que son únicas de un post específico.
     * 
     * @param long $postId El ID del post del que queremos las etiquetas únicas.
     * @return array Array de todas las etiquetas únicas.
     */
    public static function getAllUniqueTags($postId) {

        return DB::table('post_tag as pt1')
                    ->select('pt1.tag_id')
                    ->where('pt1.post_id', $postId)
                    ->whereNotExists(function ($query) use ($postId) {
                        $query->select(DB::raw(1))
                            ->from('post_tag as pt2')
                            ->whereColumn('pt2.tag_id', 'pt1.tag_id')
                            ->where('pt2.post_id', '!=', $postId);
                    })
                    ->pluck('pt1.tag_id');

    }

}