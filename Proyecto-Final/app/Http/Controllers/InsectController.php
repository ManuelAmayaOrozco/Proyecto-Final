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

class InsectController extends Controller
{

    public function showInsects() {
        $query = Insect::with('photos');
        $users = DB::table('users')->get();

        $current_user = Auth::user();

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
            $query = $query->where('protectedSpecies', true); // o 1
        }

        if (!$searchType && $search) {
            $query = $query->where('name', 'like', '%' . $search . '%');
        }

        $insects = $query->get();

        return view('user_views.insects', compact('insects', 'users', 'current_user'));
    }

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

        return view('user_views.fullInsect', compact('insect', 'insect_user', 'users', 'current_user'));
    }

    public function showRegisterInsect() {
        return view('user_views.insertInsects');
    }

    public function deleteInsect($id) {

        $validator = Validator::make(
            ['id' => $id],
            [
                'id' => 'required|exists:App\Models\Insect,id'
            ]
        );

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        $posts = Post::where("related_insect", $id);
        $posts->delete();

        $insect = Insect::find($id);

        foreach ($insect->photos as $photo) {
            Storage::disk('public')->delete($photo->path); // elimina del disco
            $photo->delete(); // elimina de la base de datos
        }

        $insect->delete();

        return redirect()->route('home');

    }

    public function doRegisterInsect(Request $request) {
    
        // VALIDAR DATOS DE ENTRADA.
        $validator = Validator::make(
            $request->all(),
            [
                "name"=>"required||unique:App\Models\Insect,name",
                "scientificName"=>"required|unique:App\Models\Insect,scientificName",
                "family"=>"required",
                "diet"=>"required",
                "description"=> "required",
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
                "description.required" => "La descripción es obligatoria.",
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
    
        if ($request->hasFile('photo')) {
            $photos = [];
            foreach ($request->file('photo') as $uploadedPhoto) {
                $path = $uploadedPhoto->store('insects', 'public');
                $photos[] = $path;
            }
        } else {
            $photos = [];
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

        foreach ($photos as $path) {
            $insect->photos()->create(['path' => $path]);
        }

        return redirect()->route('home');

    }

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

    public function updateInsect(Request $request, $id) {
    
        // VALIDAR DATOS DE ENTRADA.
        $validator = Validator::make(
            $request->all(),
            [
                "name"=>"required",
                "scientificName"=>"required",
                "family"=>"required",
                "diet"=>"required",
                "description"=> "required",
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
                "description.required" => "La descripción es obligatoria.",
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

        // SI LOS DATOS SON INVÁLIDOS, DEVOLVER A LA PÁGINA ANTERIOR E IMPRIMIR LOS ERRORES DE VALIDACIÓN
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // SI EL USUARIO NO EXISTE, DEVOLVER A LA PÁGINA ANTERIOR E IMPRIMIR LOS ERRORES DE VALIDACIÓN
        $user = Auth::user();
        if(!$user) {
            $validator->errors()->add('credentials', 'This user does not exist, use a different ID.');
            return redirect()->route('post.showRegisterPost')->withErrors($validator)->withInput();
        }

        // BUSCAR INSECTO
        $insect = Insect::find($id);
        if (!$insect) {
            return redirect()->back()->withErrors(['not_found' => 'Insect not found.'])->withInput();
        }

        if ($request->hasFile('photo')) {
            $photos = [];
            foreach ($request->file('photo') as $uploadedPhoto) {
                $path = $uploadedPhoto->store('insects', 'public');
                $photos[] = $path;
            }
        } else {
            $photos = [];
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

        foreach ($insect->photos as $photo) {
            Storage::disk('public')->delete($photo->path); // elimina del disco
            $photo->delete(); // elimina de la base de datos
        }

        foreach ($photos as $path) {
            $insect->photos()->create(['path' => $path]);
        }

        return redirect()->route('home');

    }

}