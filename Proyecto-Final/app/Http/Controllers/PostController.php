<?php

namespace App\Http\Controllers;

use App\Enums\Status;
use App\Models\Post;
use App\Models\User;
use App\Models\Insect;
use App\Models\Comment;
use App\Models\Tag;
use App\Models\Favorite;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\TagController;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

/**
 * Controlador para la clase Post
 */
class PostController extends Controller
{

    /**
     * Función que muestra la vista de la lista de todos los posts, a la que se le
     * pueden añadir filtros para búsquedas específicas.
     * 
     * @param long tagId El ID de la etiqueta por la que se filtra la búsqueda.
     * @return view La vista de la lista de posts.
     */
    public function showPosts($tagId = null) {

        $query = Post::with(['tags', 'likedByUsers']);
        $users = DB::table('users')->get();
        $insects = DB::table('insects')->get();
        $favorites = DB::table('favorites')->get();

        $current_user = Auth::user();

        // FILTROS DE BÚSQUEDA
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
                ->where('id_user', $current_user->id)
                ->pluck('id_post');
            $query = $query->whereIn('id', $favoritesIds);
        }
        
        if ($searchType === 'date' && $search) {
            $query = $query->whereDate('publish_date', $search);
        }

        if (!$searchType && $search) {
            $query = $query->where('title', 'like', '%' . $search . '%');
        }

        // ORDENAR DEL MÁS NUEVO AL MÁS ANTIGUO
        $query = $query->orderBy('created_at', 'desc');

        // PAGINACIÓN: muestra 5 posts por página y conserva los filtros en la URL
        $posts = $query->paginate(3)->appends(request()->all());

        // PREPARACIÓN DESCRIPCIÓN
        foreach ($posts as $post) {
            if (empty($post->description) || is_null($post->description)) {
                $post->description = json_encode(['blocks' => []]);
            } else {
                try {
                    $decodedDescription = json_decode($post->description);
                    if (json_last_error() !== JSON_ERROR_NONE) {
                        $post->description = json_encode(['blocks' => []]);
                    }
                } catch (Exception $e) {
                    $post->description = json_encode(['blocks' => []]);
                }
            }
        }

        return view('user_views.posts', compact('posts', 'users', 'insects', 'current_user', 'favorites'));
    }

    /**
     * Función que muestra la vista del inicio de la aplicación.
     * 
     * @return view La vista del inicio de la aplicación.
     */
    public function showHome() {
        $posts = DB::table('posts')->get();
        $users = DB::table('users')->get();
        $insects = DB::table('insects')->get();

        $current_user = Auth::user();

        // POST DIARIO
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

    /**
     * Función que muestra la vista detallada de un post en específico.
     * 
     * @param long $id El ID del post del que deseamos ver la vista.
     * @return view La vista detallada del post.
     */
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

    /**
     * Función que muestra la vista para registrar un nuevo post.
     * 
     * @return view La vista para registrar un nuevo post.
     */
    public function showRegisterPost() {
        $insects = DB::table('insects')->get();

        return view('user_views.insertPosts', compact('insects'));
    }

    /**
     * Función que actualiza el número de likes de un post, incrementándolo.
     * 
     * @param long $id El ID del post al que se da like.
     * @return view La vista anterior.
     */
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

    /**
     * Función que actualiza el número de likes de un post, decrementándolo.
     * 
     * @param long $id El ID del post al que se le quita like.
     * @return view La vista anterior.
     */
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

    /**
     * Función que añade un post a los favoritos de un usuario.
     * 
     * @param long $id El ID del post al que se añade como favorito.
     * @return view La vista anterior.
     */
    public function newFavorite($id) {
    
        $post = Post::find($id);

        $current_user_id = Auth::id();

        $createFavorite = new Favorite();
        $createFavorite->id_post = $id;
        $createFavorite->id_user = $current_user_id;
        $createFavorite->save();

        return redirect()->back();

    }

    /**
     * Función que quita un post de los favoritos de un usuario.
     * 
     * @param long $id El ID del post al que se quita como favorito.
     * @return view La vista anterior.
     */
    public function removeFavorite($id) {

        $current_user_id = Auth::id();

        DB::table('favorites')->where('id_post', $id)->where('id_user', $current_user_id)->delete();

        return redirect()->back();

    }

    /**
     * Función para eliminar un post específico de la base de datos.
     * 
     * @param long $id El ID del post que deseamos eliminar.
     * @return view La vista de la lista de posts para ver que el post
     * fue eliminado correctamente.
     */
    public function deletePost($id) {

        $post = Post::findOrFail($id);

        $post->deleteCompletely();

        return redirect()->route('post.showPosts');
        
    }

    /**
     * Función que registra un nuevo post en la base de datos.
     * 
     * @param request $request Request obtenida del formulario que provee
     * los datos necesarios para crear el post.
     * @return view La vista de la lista de posts para ver que el post
     * fue añadido correctamente.
     */
    public function doRegisterPost(Request $request) {
    
        // VALIDAR DATOS DE ENTRADA.
        $validator = Validator::make(
            $request->all(),
            [
                "title"=>"required|min:1|max:50",
                "photo"=>"required|image|mimes:jpeg,png,jpg|max:2048",
                "insect"=>"required",
                "latitude" => "nullable|numeric",
                "longitude" => "nullable|numeric"
            ],[
                "title.required" => "El título es obligatorio.",
                "title.min" => "El título ha de tener por lo menos un carácter.",
                "title.max" => "El título no puede tener más de 50 carácteres.",
                "photo.required" => "La imagen es obligatoria.",
                "photo.image" => "La foto ha de ser una imagen.",
                "photo.mimes" => "La foto ha de ser jpg/png/jpg.",
                "photo.max" => "La foto no puede ser mayor de 2048px.",
                "insect.required" => "El insecto relacionado es obligatorio.",
                "latitude.numeric" => "La latitud ha de ser numérica.",
                "longitude.numeric" => "La longitude ha de ser numérica."
            ]
        );

        // VALIDACIÓN DESCRIPCIÓN
        $description = json_decode($request->input('description'), true);

        if (
            !$description ||
            !isset($description['blocks']) ||
            !is_array($description['blocks']) ||
            count(array_filter($description['blocks'], fn($block) => !empty(trim($block['data']['text'] ?? '')))) === 0
        ) {
            $validator->errors()->add('description', 'La descripción no puede estar vacía.');
            return redirect()->back()->withErrors($validator)->withInput();
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

        // SUBIR IMAGEN A IMGBB
        $photoUrl = null;
        if ($request->hasFile('photo')) {
            $uploadedPhoto = $request->file('photo');
            $imageData = base64_encode(file_get_contents($uploadedPhoto->getRealPath()));

            $response = Http::asForm()->post('https://api.imgbb.com/1/upload', [
                'key' => env('IMGBB_API_KEY'),
                'image' => $imageData,
                'name' => pathinfo($uploadedPhoto->getClientOriginalName(), PATHINFO_FILENAME),
            ]);

            if (!$response->successful()) {
                $error = $response->json();
                \Log::error('ImgBB upload failed', ['response' => $error]);

                $errorMessage = $error['error']['message'] ?? 'Error desconocido al subir la imagen.';
                return redirect()->back()->withErrors(['photo' => 'Error al subir imagen a Imgbb: ' . $errorMessage])->withInput();
            }

            $body = $response->json();
            $photoUrl = $body['data']['url'] ?? null;

            if (!$photoUrl) {
                return redirect()->back()->withErrors(['photo' => 'No se pudo obtener la URL de Imgbb'])->withInput();
            }
        }

        // SI LOS DATOS SON VÁLIDOS (SI EL REGISTRO SE HA REALIZADO CORRECTAMENTE) CARGAR LA VIEW
        $datosPost = $request->all();
        $post = new Post();
        $post->title = $datosPost['title'];
        $post->description = $datosPost['description'];
        $post->publish_date = Carbon::now();
        $post->n_likes = 0;
        $post->belongs_to = Auth::id();
        $post->related_insect = $datosPost['insect'];
        $post->latitude = $datosPost['latitude'] ?? null;
        $post->longitude = $datosPost['longitude'] ?? null;
        $post->photo = $photoUrl;
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

        return redirect()->route('post.showPosts');

    }

    /**
     * Función que muestra la vista para actualizar un post.
     * 
     * @param long El ID del post que se va a actualizar.
     * @return view La vista para actualizar un post.
     */
    public function showUpdatePost($id) {

        $post = Post::with('tags')->findOrFail($id);

        $insects = Insect::all();

        return view('user_views.updatePosts', compact('post', 'insects'));
    }

    /**
     * Función que actualiza un post de la base de datos.
     * 
     * @param request $request Request obtenida del formulario que provee
     * los datos necesarios para crear el post.
     * @param long $id ID del post que se va a actualizar.
     * @return view La vista de la lista de posts para ver los cambios realizados.
     */
    public function updatePost(Request $request, $id) {
        $post = Post::with('tags')->findOrFail($id);

        // VALIDAR DATOS DE ENTRADA.
        $validator = Validator::make(
            $request->all(),
            [
                "title" => "required|min:1|max:50",
                "insect" => "required",
                "photo" => "nullable|image|mimes:jpeg,png,jpg|max:2048",
                "latitude" => "nullable|numeric",
                "longitude" => "nullable|numeric"
            ],
            [
                "title.required" => "El título es obligatorio.",
                "title.min" => "El título ha de tener por lo menos un carácter.",
                "title.max" => "El título no puede tener más de 50 carácteres.",
                "insect.required" => "El insecto relacionado es obligatorio.",
                "photo.image" => "La foto ha de ser una imagen.",
                "photo.mimes" => "La foto ha de ser jpg/png/jpg.",
                "photo.max" => "La foto no puede ser mayor de 2048px.",
                "latitude.numeric" => "La latitud ha de ser numérica.",
                "longitude.numeric" => "La longitud ha de ser numérica."
            ]
        );

        // VALIDACIÓN DESCRIPCIÓN
        $description = json_decode($request->input('description'), true);

        if (
            !$description ||
            !isset($description['blocks']) ||
            !is_array($description['blocks']) ||
            count(array_filter($description['blocks'], fn($block) => !empty(trim($block['data']['text'] ?? '')))) === 0
        ) {
            $validator->errors()->add('description', 'La descripción no puede estar vacía.');
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // SI LOS DATOS SON INVÁLIDOS, DEVOLVER A LA PÁGINA ANTERIOR E IMPRIMIR LOS ERRORES DE VALIDACIÓN
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // SI EL USUARIO NO EXISTE, DEVOLVER A LA PÁGINA ANTERIOR E IMPRIMIR LOS ERRORES DE VALIDACIÓN
        $user = Auth::user();
        if (!$user) {
            $validator->errors()->add('credentials', 'El usuario no está autenticado.');
            return redirect()->route('post.showRegisterPost')->withErrors($validator)->withInput();
        }

        // GUARDAR NUEVA FOTO (si se carga una nueva)
        if ($request->hasFile('photo')) {
            $uploadedPhoto = $request->file('photo');
            $imageData = base64_encode(file_get_contents($uploadedPhoto->getRealPath()));

            $response = Http::asForm()->post('https://api.imgbb.com/1/upload', [
                'key' => env('IMGBB_API_KEY'),
                'image' => $imageData,
                'name' => pathinfo($uploadedPhoto->getClientOriginalName(), PATHINFO_FILENAME),
            ]);

            if (!$response->successful()) {
                $body = $response->json();
                \Log::error('Error subiendo imagen a Imgbb: ', $body);
                $errorMessage = $body['error']['message'] ?? 'Error desconocido al subir imagen.';
                return redirect()->back()->withErrors(['photo' => 'Error al subir imagen a Imgbb: ' . $errorMessage])->withInput();
            }

            $body = $response->json();
            if (!isset($body['data']['url'])) {
                return redirect()->back()->withErrors(['photo' => 'No se pudo obtener la URL de Imgbb'])->withInput();
            }

            $post->photo = $body['data']['url'];
        }

        // SI LOS DATOS SON VÁLIDOS (SI EL REGISTRO SE HA REALIZADO CORRECTAMENTE) CARGAR LA VIEW
        $post->title = $request->input('title');
        $post->description = $request->input('description');
        $post->related_insect = $request->input('insect');
        $post->latitude = $request->input('latitude');
        $post->longitude = $request->input('longitude');
        $post->save();

        // ACTUALIZAR TAGS
        // 1. Desvincular todos los tags actuales
        $post->tags()->detach();

        // 2. Procesar nuevos tags
        $tagsRaw = strtolower(trim($request->input('tags', '')));
        if (!empty($tagsRaw)) {
            $listaTags = array_filter(array_map('trim', explode(',', $tagsRaw)));
            foreach ($listaTags as $tag) {
                TagController::registerTag($tag); // Crea el tag si no existe
                $tagId = TagController::getTag($tag); // Obtiene el ID del tag
                $post->tags()->attach($tagId);
            }
        }

        return redirect()->route('post.showPosts');
    }


    /**
     * Función que actualiza el post diario, elegido aleatoriamente de entre
     * todos los posts.
     * 
     * @return status Estado que advierte de si el post ha sido actualizado o no.
     */
    public static function updateDailyPost() {

        // Resetear todos los posts
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