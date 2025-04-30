<?php

namespace App\Http\Controllers;

use App\Enums\Status;
use App\Models\Post;
use App\Models\User;
use App\Models\Comment;
use App\Models\Tag;
use App\Models\Favorite;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\TagController;

class PostController extends Controller
{

    public function showPosts($tagId = null) {

        $query = Post::with('tags');
        $users = DB::table('users')->get();
        $insects = DB::table('insects')->get();
        $favorites = DB::table('favorites')->get();

        $current_user_id = Auth::id();

        if ($tagId != null) {
            // Búsqueda por etiqueta
            $postTagIds = DB::table('post_tag')
                ->where('tag_id', $tagId)
                ->pluck('post_id');
            $query = $query->whereIn('id', $postTagIds);
        }
        
        $searchType = request()->get('searchtype');
        $search = request()->get('search');
        
        if ($searchType === 'user' && $search) {
            $userIds = DB::table('users')
                ->where('name', 'like', '%' . $search . '%')
                ->pluck('id');
            $query = $query->whereIn('belongs_to', $userIds);
        }
        
        if ($searchType === 'insect' && $search) {
            $insectIds = DB::table('insects')
                ->where('name', 'like', '%' . $search . '%')
                ->pluck('id');
            $query = $query->whereIn('related_insect', $insectIds);
        }

        if ($searchType === 'tag' && $search) {
            $tagIds = DB::table('tags')
                ->where('name', 'like', '%' . $search . '%')
                ->pluck('id');
        
            $postTagIds = DB::table('post_tag') // tabla de relación
                ->whereIn('tag_id', $tagIds)
                ->pluck('post_id');
        
            $query = $query->whereIn('id', $postTagIds);
        }
        
        if ($searchType === 'favorites') {
            $favoritesIds = DB::table('favorites')
                ->where('id_user', $current_user_id)
                ->pluck('id_post');
            $query = $query->whereIn('id', $favoritesIds);
        }
        
        // Si no hay tipo específico pero hay búsqueda por texto
        if (!$searchType && $search) {
            $query = $query->where('description', 'like', '%' . $search . '%');
        }

        $posts = $query->get();

        return view('user_views.posts', compact('posts', 'users', 'insects', 'current_user_id', 'favorites'));
    }

    public function showHome() {
        $posts = DB::table('posts')->get();
        $users = DB::table('users')->get();
        $insects = DB::table('insects')->get();

        $current_user_id = Auth::id();

        $dailyPost = Post::where('dailyPost', true)->first();

        // Si no hay dailyPost, forzar creación de uno nuevo
        if (!$dailyPost) {
            Post::where('dailyPost', true)->update(['dailyPost' => false]);

            $postIds = Post::pluck('id');
            if ($postIds->isNotEmpty()) {
                $randomPostId = $postIds->random();
                $dailyPost = Post::find($randomPostId);
                $dailyPost->dailyPost = true;
                $dailyPost->save();
            }
        }

        return view('home', compact('posts', 'users', 'insects', 'current_user_id', 'dailyPost'));
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

        $isFavorite = DB::table('favorites')
                        ->where('id_post', $id)
                        ->where('id_user', $current_user_id)
                        ->first();

        return view('user_views.fullPost', compact('post', 'post_user', 'post_insect', 'post_insect_id', 'comments', 'users', 'current_user_id', 'isFavorite'));
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

    public function newFavorite($id) {
    
        $post = Post::find($id);

        $current_user_id = Auth::id();

        $createFavorite = new Favorite();
        $createFavorite->id_post = $id;
        $createFavorite->id_user = $current_user_id;
        $createFavorite->save();

        return redirect()->back();

    }

    public function removeFavorite($id) {

        $current_user_id = Auth::id();

        DB::table('favorites')->where('id_post', $id)->where('id_user', $current_user_id)->delete();

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

        $uniqueTags = TagController::getAllUniqueTags($id);

        DB::table('post_tag')->where('post_id', $id)->delete();

        if ($uniqueTags->isNotEmpty()) {
            Tag::whereIn('id', $uniqueTags)->delete();
        }

        $post->delete();

        return redirect()->route('home');

    }

    public function doRegisterPost(Request $request) {
    
        // VALIDAR DATOS DE ENTRADA.
        $validator = Validator::make(
            $request->all(),
            [
                "title"=>"required|min:1|max:50",
                "description"=> "required",
                "photo"=>"required",
                "insect"=>"required"
            ],[
                "title.required" => "El título es obligatorio.",
                "title.min" => "El título ha de tener por lo menos un carácter.",
                "title.max" => "El título no puede tener más de 50 carácteres.",
                "description.required" => "La descripción es obligatoria.",
                "photo.required" => "La imagen es obligatoria.",
                "insect.required" => "El insecto relacionado es obligatorio."
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

        //CREACIÓN DE TAGS
        $tags = strtolower($datosPost['tags']);
        $listaTags = array_map('trim', explode(',', $tags));

        foreach ($listaTags as $tag) {

            TagController::registerTag($tag);

            $tagId = TagController::getTag($tag);

            $post->tags()->attach($tagId);

        }

        return redirect()->route('home');

    }

    public static function updateDailyPost() {

        // Resetear todos
        Post::where('dailyPost', true)->update(['dailyPost' => false]);

        // Obtener todos los IDs
        $postIds = Post::pluck('id');

        if ($postIds->isNotEmpty()) {
            $randomPostId = $postIds->random();
            $post = Post::find($randomPostId);
            $post->update(['dailyPost' => true]);

            return response()->json(['status' => 'updated', 'post_id' => $randomPostId]);
        }

        return response()->json(['status' => 'no_posts']);

    }

}