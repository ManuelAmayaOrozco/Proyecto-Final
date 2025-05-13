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

        $query = Post::with(['tags', 'likedByUsers']);
        $users = DB::table('users')->get();
        $insects = DB::table('insects')->get();
        $favorites = DB::table('favorites')->get();

        $current_user = Auth::user();

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
        
        if ($searchType === 'date' && $search) {
            $query = $query->whereDate('created_at', $search);
        }

        // Si no hay tipo específico pero hay búsqueda por texto
        if (!$searchType && $search) {
            $query = $query->where('title', 'like', '%' . $search . '%');
        }

        // PAGINACIÓN: muestra 5 posts por página y conserva los filtros en la URL
        $posts = $query->paginate(5)->appends(request()->all());

        // Antes de pasar la descripción, asegúrate de que es un JSON válido
        foreach ($posts as $post) {
            // Si no hay descripción o es nula, asigna un objeto JSON vacío con bloques vacíos
            if (empty($post->description) || is_null($post->description)) {
                $post->description = json_encode(['blocks' => []]);
            } else {
                // Asegúrate de que la descripción es un JSON válido antes de pasarlo al frontend
                try {
                    $decodedDescription = json_decode($post->description);
                    if (json_last_error() !== JSON_ERROR_NONE) {
                        // Si el JSON no es válido, asigna un objeto JSON vacío
                        $post->description = json_encode(['blocks' => []]);
                    }
                } catch (Exception $e) {
                    // Si hay un error al decodificar, asigna un JSON vacío
                    $post->description = json_encode(['blocks' => []]);
                }
            }
        }

        return view('user_views.posts', compact('posts', 'users', 'insects', 'current_user', 'favorites'));
    }

    public function showHome() {
        $posts = DB::table('posts')->get();
        $users = DB::table('users')->get();
        $insects = DB::table('insects')->get();

        $current_user = Auth::user();

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

        return view('home', compact('posts', 'users', 'insects', 'current_user', 'dailyPost'));
    }

    public function showFullPost($id) {
        $post = Post::with('likedByUsers')->find($id);

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


        $current_user = Auth::user();

        if ($current_user) {
            $isFavorite = DB::table('favorites')
                        ->where('id_post', $id)
                        ->where('id_user', $current_user->id)
                        ->first();
        } else {
            $isFavorite = null;
        }

        return view('user_views.fullPost', compact('post', 'post_user', 'post_insect', 'post_insect_id', 'comments', 'users', 'current_user', 'isFavorite'));
    }

    public function showRegisterPost() {
        $insects = DB::table('insects')->get();

        return view('user_views.insertPosts', compact('insects'));
    }

    public function updateLike($id) {

        $post = Post::findOrFail($id);
        $user = auth()->user();

        // Verificar si el usuario ya dio like
        if (!$post->likedByUsers->contains($user->id)) {
            $post->likedByUsers()->attach($user->id); // Agrega el like
            $post->increment('n_likes');
        }

        return redirect()->back();
    }

    public function removeLike($id) {

        $post = Post::findOrFail($id);
        $user = auth()->user();

        // Verificar si el usuario ya dio like
        if ($post->likedByUsers->contains($user->id)) {
            $post->likedByUsers()->detach($user->id); // Quita el like

            if ($post->n_likes > 0) {
                $post->decrement('n_likes');
            }
        }

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
        $tagsRaw = strtolower(trim($datosPost['tags'] ?? ''));

        if (!empty($tagsRaw)) {
            $listaTags = array_filter(array_map('trim', explode(',', $tagsRaw))); // Limpia tags vacíos
        
            foreach ($listaTags as $tag) {
                TagController::registerTag($tag);
                $tagId = TagController::getTag($tag);
                $post->tags()->attach($tagId);
            }
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