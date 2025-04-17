<?php

namespace App\Http\Controllers;

use App\Enums\Status;
use App\Models\Post;
use App\Models\User;
use App\Models\Comment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{

    public function showPosts() {
        $posts = DB::table('posts')->get();
        $users = DB::table('users')->get();
        $insects = DB::table('insects')->get();

        $current_user_id = Auth::id();
        return view('user_views.posts', compact('posts', 'users', 'insects', 'current_user_id'));
    }

    public function showHome() {
        $posts = DB::table('posts')->get();
        $users = DB::table('users')->get();

        $current_user_id = Auth::id();
        return view('home', compact('posts', 'users', 'current_user_id'));
    }

    public function showFullPost($id) {
        $post = null;

        $posts = Post::all();

        foreach ($posts as $posto) {

            if ($posto->id == $id) {

                $post = $posto;

            }

        }

        $post_user = null;

        $users = DB::table('users')->get();

        foreach ($users as $user) {

            if ($user->id == $post->belongs_to) {

                $post_user = $user->name;

            }

        }

        $post_insect = null;

        $insects = DB::table('insects')->get();

        foreach ($insects as $insect) {

            if ($insect->id == $post->related_insect) {

                $post_insect = $insect->name;
                $post_insect_id = $insect->id;

            }

        }

        //$comments = DB::table('comments')->where("post_id", $id);
        $comments = $post->comments()->get();


        $current_user_id = Auth::id();

        return view('user_views.fullPost', compact('post', 'post_user', 'post_insect', 'post_insect_id', 'comments', 'users', 'current_user_id'));
    }

    public function showRegisterPost() {
        $insects = DB::table('insects')->get();

        return view('user_views.insertPosts', compact('insects'));
    }

    public function updateLike($id) {
    
        $post = Post::find($id);

        $post->increment('n_likes');

        return redirect()->back();

    }

    public function deletePost($id) {

        $validator = Validator::make(
            ['id' => $id],
            [
                'id' => 'required|exists:App\Models\Post,id'
            ]
        );

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        $comments = Comment::where("post_id", $id);
        $comments->delete();

        $post = Post::find($id);

        $image = $post->photo;
        Storage::disk('public')->delete($image);

        $post->delete();

        return redirect()->route('home');

    }

    public function doRegisterPost(Request $request) {
    
        // VALIDAR DATOS DE ENTRADA.
        $validator = Validator::make(
            $request->all(),
            [
                "title"=>"required",
                "description"=> "required",
                "photo"=>"required",
                "insect"=>"required"
            ],[
                "title.required" => "The :attribute is required.",
                "description.required" => "The :attribute is required.",
                "photo.required" => "The :attribute is required.",
                "insect.required" => "The :attribute is required."
            ]
        );
    
        if ($request->hasFile('photo')) {
            $photo = $request['photo']->store('posts', 'public');
        } else {
            $photo = null;
        }

        // SI LOS DATOS SON INVÁLIDOS, DEVOLVER A LA PÁGINA ANTERIOR E IMPRIMIR LOS ERRORES DE VALIDACIÓN
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        // SI EL USUARIO NO EXISTE, DEVOLVER A LA PÁGINA ANTERIOR E IMPRIMIR LOS ERRORES DE VALIDACIÓN
        $user = Auth::user();
        if(!$user) {
            $validator->errors()->add('credentials', 'This user does not exist, use a different ID.');
            return redirect()->route('post.showRegisterPost')->withErrors($validator)->withInput();
        }

        // SI LOS DATOS SON VÁLIDOS (SI EL REGISTRO SE HA REALIZADO CORRECTAMENTE) CARGAR LA VIEW
        $datosPost = $request->all();
        $post = new Post();
        $post->title = $datosPost['title'];
        $post->description = $datosPost['description'];
        $post->publish_date = date('d-m-y h:i:s');
        $post->n_likes = 0;
        $post->belongs_to = Auth::id();
        $post->related_insect = $datosPost['insect'];
        $post->photo = $photo;
        $post->save();

        return redirect()->route('home');

    }

}