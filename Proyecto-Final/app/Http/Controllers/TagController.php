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

class TagController extends Controller
{

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

    public static function getTag($newTag) {

        $tagId = DB::table('tags')
                    ->where('name', '=', $newTag)
                    ->value('id');

        return $tagId;

    }

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