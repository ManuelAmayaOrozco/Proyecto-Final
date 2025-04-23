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

}