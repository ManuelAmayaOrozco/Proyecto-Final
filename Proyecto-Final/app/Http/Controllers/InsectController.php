<?php

namespace App\Http\Controllers;

use App\Enums\Status;
use App\Models\Insect;
use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;

/**
 * Controlador para la clase Insect
 */
class InsectController extends Controller
{
    /**
     * Función que muestra la vista de la lista de todos los insectos, a la que se le
     * pueden añadir filtros para búsquedas específicas.
     * 
     * @return view La vista de la lista de insectos.
     */
    public function showInsects() {
        $query = Insect::with('photos');
        $users = DB::table('users')->get();
        $current_user = Auth::user();

        // FILTROS DE BÚSQUEDA
        $searchType = request()->get('searchtype');
        $search = request()->get('search');

        if ($searchType === 'user' && $search) {
            $userIds = DB::table('users')
                ->where('name', 'like', '%' . $search . '%')
                ->pluck('id');
            $query = $query->whereIn('registered_by', $userIds);
        }

        if ($searchType === 'scientificName' && $search) {
            $query = $query->where('scientificName', 'like', '%' . $search . '%');
        }

        if ($searchType === 'family' && $search) {
            $query = $query->where('family', 'like', '%' . $search . '%');
        }

        if ($searchType === 'diet' && $search) {
            $query = $query->where('diet', 'like', '%' . $search . '%');
        }

        if ($searchType === 'inDanger') {
            $query = $query->where('protectedSpecies', true);
        }

        if (!$searchType && $search) {
            $query = $query->where('name', 'like', '%' . $search . '%');
        }

        // ORDENAR DEL MÁS NUEVO AL MÁS ANTIGUO
        $query = $query->orderBy('created_at', 'desc');

        // PAGINACIÓN: muestra 2 insectos por página y conserva los filtros en la URL
        $insects = $query->paginate(2)->appends(request()->all());

        return view('user_views.insects', compact('insects', 'users', 'current_user'));
    }

    /**
     * Función que muestra la vista detallada de un insecto en específico.
     * 
     * @param long $id El ID del insecto del que deseamos ver la vista.
     * @return view La vista detallada del insecto.
     */
    public function showFullInsect($id) {
        $insect = null;

        $insects = Insect::all();

        foreach ($insects as $insecto) {

            if ($insecto->id == $id) {

                $insect = $insecto;

            }

        }

        $insect_user = null;

        $users = DB::table('users')->get();

        foreach ($users as $user) {

            if ($user->id == $insect->registered_by) {

                $insect_user = $user->name;

            }

        }

        $current_user = Auth::user();

        $insect_posts = Post::where('related_insect', $insect->id)->get();

        return view('user_views.fullInsect', compact('insect', 'insect_user', 'users', 'current_user', 'insect_posts'));
    }

    /**
     * Función que muestra la vista para registrar un nuevo insecto.
     * 
     * @return view La vista para registrar un nuevo insecto.
     */
    public function showRegisterInsect() {
        return view('user_views.insertInsects');
    }

    /**
     * Función para eliminar un insecto específico de la base de datos.
     * 
     * @param long $id El ID del insecto que deseamos eliminar.
     * @return view La vista de la lista de insectos para ver que el insecto
     * fue eliminado correctamente.
     */
    public function deleteInsect($id) {

        // VALIDAR DATOS DE ENTRADA.
        $validator = Validator::make(
            ['id' => $id],
            [
                'id' => 'required|exists:App\Models\Insect,id'
            ]
        );

        // SI LOS DATOS SON INVÁLIDOS, DEVOLVER A LA PÁGINA ANTERIOR E IMPRIMIR LOS ERRORES DE VALIDACIÓN.
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        // ELIMINAMOS LOS POSTS RELACIONADOS CON EL INSECTO
        $posts = Post::where("related_insect", $id)->get();

        foreach ($posts as $post) {
            $post->deleteCompletely();
        }

        $insect = Insect::find($id);

        // ELIMINAMOS TODAS LAS IMÁGENES DEL INSECTO
        foreach ($insect->photos as $photo) {
            $photo->delete(); // elimina de la base de datos
        }

        $insect->delete();

        return redirect()->route('insect.showInsects');

    }

    /**
     * Función que registra un nuevo insecto en la base de datos.
     * 
     * @param request $request Request obtenida del formulario que provee
     * los datos necesarios para crear el insecto.
     * @return view La vista de la lista de insectos para ver que el insecto
     * fue añadido correctamente.
     */
    public function doRegisterInsect(Request $request) {
    
        // VALIDAR DATOS DE ENTRADA.
        $validator = Validator::make(
            $request->all(),
            [
                "name"=>"required|unique:App\Models\Insect,name",
                "scientificName"=>"required|unique:App\Models\Insect,scientificName",
                "family"=>"required",
                "diet"=>"required",
                "n_spotted"=>"required|min:1",
                "maxSize"=>"required|min:0.01",
                "photo" => "required|array",
                "photo.*" => "image|mimes:jpeg,png,jpg|max:2048",
            ],[
                "name.required" => "El nombre es obligatorio.",
                "name.unique" => "Ese nombre ya está en uso.",
                "scientificName.required" => "El nombre científico es obligatorio.",
                "scientificName.unique" => "Ese nombre científico ya está en uso.",
                "family.required" => "El nombre de la familia es obligatorio.",
                "diet.required" => "El tipo de dieta es obligatorio.",
                "n_spotted.required" => "El número de ejemplares vistos es obligatorio.",
                "n_spotted.min" => "El número de ejemplares vistos no puede ser menor que 1.",
                "maxSize.required" => "El tamaño máximo documentado es obligatorio.",
                "maxSize.min" => "El tamaño máximo documentado no puede ser menor a 0.01cm.",
                "photo.required" => "La foto es obligatoria.",
                "photo.array" => "Las fotos han de venir en formato array.",
                "photo.*.image" => "La foto ha de ser una imagen.",
                "photo.*.mimes" => "La foto ha de ser jpg/png/jpg.",
                "photo.*.max" => "La foto no puede ser mayor de 2048px."
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

        // SE GUARDAN LAS FOTOS QUE SEAN AÑADIDAS PARA EL INSECTO EN POSTIMAGE
        $photos = [];
        if ($request->hasFile('photo')) {
            foreach ($request->file('photo') as $uploadedPhoto) {
                $response = Http::attach(
                    'file', file_get_contents($uploadedPhoto->getRealPath()), $uploadedPhoto->getClientOriginalName()
                )->post('https://postimages.org/json/rr');

                if ($response->successful()) {
                    $responseBody = $response->json();

                    // Cambia según estructura real (verifica con dump($responseBody))
                    $imageUrl = $responseBody['image']['url'] ?? null;

                    if ($imageUrl) {
                        $photos[] = $imageUrl;
                    } else {
                        return redirect()->back()->withErrors(['photo' => 'No se pudo obtener la URL de Postimage'])->withInput();
                    }
                } else {
                    return redirect()->back()->withErrors(['photo' => 'Error al subir la imagen a Postimage'])->withInput();
                }
            }
        }

        // SI EL USUARIO NO EXISTE, DEVOLVER A LA PÁGINA ANTERIOR E IMPRIMIR LOS ERRORES DE VALIDACIÓN
        $user = Auth::user();
        if(!$user) {
            $validator->errors()->add('credentials', 'This user does not exist, use a different ID.');
            return redirect()->route('insect.showRegisterInsect')->withErrors($validator)->withInput();
        }

        // SI LOS DATOS SON VÁLIDOS (SI EL REGISTRO SE HA REALIZADO CORRECTAMENTE) CARGAR LA VIEW
        $datosPost = $request->all();
        $insect = new Insect();
        $insect->registered_by = Auth::id();
        $insect->name = $datosPost['name'];
        $insect->scientificName = $datosPost['scientificName'];
        $insect->family = $datosPost['family'];
        $insect->diet = $datosPost['diet'];
        $insect->description = $datosPost['description'];
        $insect->n_spotted = $datosPost['n_spotted'];
        $insect->maxSize = $datosPost['maxSize'];
        $insect->protectedSpecies = $datosPost['protectedSpecies'];
        $insect->save();

        foreach ($photos as $url) {
            $insect->photos()->create(['path' => $url]);
        }

        return redirect()->route('insect.showInsects');

    }

    /**
     * Función que muestra la vista para actualizar un insecto.
     * 
     * @param long El ID del insecto que se va a actualizar.
     * @return view La vista para actualizar un insecto.
     */
    public function showUpdateInsect($id) {

        $insect = null;

        $insects = Insect::all();

        foreach ($insects as $insecto) {

            if ($insecto->id == $id) {

                $insect = $insecto;

            }

        }

        return view('user_views.updateInsects', compact('insect'));
    }

    /**
    * Función que actualiza un insecto de la base de datos.
    * 
    * @param long $id El ID del insecto que se va a actualizar.
    * @param request $request Request obtenida del formulario que provee
    * los datos necesarios para actualizar el insecto.
    * @return view La vista de la lista de insectos para ver que el insecto
    * fue actualizado correctamente.
    */
    public function updateInsect(Request $request, $id) {
    
        // VALIDAR DATOS DE ENTRADA.
        $validator = Validator::make(
            $request->all(),
            [
                "name"=>"required",
                "scientificName"=>"required",
                "family"=>"required",
                "diet"=>"required",
                "n_spotted"=>"required|min:1",
                "maxSize"=>"required|min:0.01",
                "photo" => "required|array",
                "photo.*" => "image|mimes:jpeg,png,jpg|max:2048",
            ],
            [
                "name.required" => "El nombre es obligatorio.",
                "name.unique" => "Ese nombre ya está en uso.",
                "scientificName.required" => "El nombre científico es obligatorio.",
                "scientificName.unique" => "Ese nombre científico ya está en uso.",
                "family.required" => "El nombre de la familia es obligatorio.",
                "diet.required" => "El tipo de dieta es obligatorio.",
                "n_spotted.required" => "El número de ejemplares vistos es obligatorio.",
                "n_spotted.min" => "El número de ejemplares vistos no puede ser menor que 1.",
                "maxSize.required" => "El tamaño máximo documentado es obligatorio.",
                "maxSize.min" => "El tamaño máximo documentado no puede ser menor a 0.01cm.",
                "photo.required" => "La foto es obligatoria.",
                "photo.array" => "La foto ha de ser en formato array.",
                "photo.*.image" => "La foto ha de ser una imagen.",
                "photo.*.mimes" => "La foto ha de ser jpg/png/jpg.",
                "photo.*.max" => "La foto no puede ser mayor de 2048px."
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
        if(!$user) {
            $validator->errors()->add('credentials', 'This user does not exist, use a different ID.');
            return redirect()->route('insect.showRegisterInsect')->withErrors($validator)->withInput();
        }

        // BUSCAR INSECTO
        $insect = Insect::find($id);
        if (!$insect) {
            return redirect()->back()->withErrors(['not_found' => 'Insect not found.'])->withInput();
        }

        // SE ACTUALIZAN LAS IMÁGENES DEL INSECTO EN POSTIMAGE
        $photos = [];
        if ($request->hasFile('photo')) {
            foreach ($request->file('photo') as $uploadedPhoto) {
                $response = Http::attach(
                    'file', file_get_contents($uploadedPhoto->getRealPath()), $uploadedPhoto->getClientOriginalName()
                )->post('https://postimages.org/json/rr');

                if ($response->successful()) {
                    $responseBody = $response->json();

                    // Ajusta según la estructura de la respuesta real
                    $imageUrl = $responseBody['image']['url'] ?? null;

                    if ($imageUrl) {
                        $photos[] = $imageUrl;
                    } else {
                        return redirect()->back()->withErrors(['photo' => 'No se pudo obtener la URL de Postimage'])->withInput();
                    }
                } else {
                    return redirect()->back()->withErrors(['photo' => 'Error al subir la imagen a Postimage'])->withInput();
                }
            }
        }

        // SI LOS DATOS SON VÁLIDOS (SI EL REGISTRO SE HA REALIZADO CORRECTAMENTE) CARGAR LA VIEW
        $datosPost = $request->all();
        $insect->name = $datosPost['name'];
        $insect->scientificName = $datosPost['scientificName'];
        $insect->family = $datosPost['family'];
        $insect->diet = $datosPost['diet'];
        $insect->description = $datosPost['description'];
        $insect->n_spotted = $datosPost['n_spotted'];
        $insect->maxSize = $datosPost['maxSize'];
        $insect->protectedSpecies = $datosPost['protectedSpecies'];
        $insect->save();

        // ELIMINA LAS IMÁGENES DEL INSECTO QUE YA NO SE UTILICEN
        foreach ($insect->photos as $photo) {
            $photo->delete();
        }

        // GUARDAR NUEVAS URLs DE LAS IMÁGENES
        foreach ($photos as $url) {
            $insect->photos()->create(['path' => $url]);
        }

        return redirect()->route('insect.showInsects');

    }

}