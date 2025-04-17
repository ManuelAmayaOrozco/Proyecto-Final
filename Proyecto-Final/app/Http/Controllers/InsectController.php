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
        $insects = DB::table('insects')->get();
        $users = DB::table('users')->get();

        $current_user_id = Auth::id();
        return view('user_views.insects', compact('insects', 'users', 'current_user_id'));
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

        $current_user_id = Auth::id();

        return view('user_views.fullInsect', compact('insect', 'insect_user', 'users', 'current_user_id'));
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

        $image = $insect->photo;
        Storage::disk('public')->delete($image);

        $insect->delete();

        return redirect()->route('home');

    }

    public function doRegisterInsect(Request $request) {
    
        // VALIDAR DATOS DE ENTRADA.
        $validator = Validator::make(
            $request->all(),
            [
                "name"=>"required",
                "scientificName"=>"required",
                "family"=>"required",
                "diet"=>"required",
                "description"=> "required",
                "n_spotted"=>"required",
                "maxSize"=>"required",
                "photo"=>"required"
            ],[
                "name.required" => "The :attribute is required.",
                "scientificName.required" => "The :attribute is required.",
                "family.required" => "The :attribute is required.",
                "diet.required" => "The :attribute is required.",
                "description.required" => "The :attribute is required.",
                "n_spotted.required" => "The :attribute is required.",
                "maxSize.required" => "The :attribute is required.",
                "photo.required" => "The :attribute is required.",
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
        $insect->photo = $photo;
        $insect->save();

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
                "name" => "required",
                "scientificName" => "required",
                "family" => "required",
                "diet" => "required",
                "description" => "required",
                "n_spotted" => "required",
                "maxSize" => "required"
            ],
            [
                "name.required" => "The :attribute is required.",
                "scientificName.required" => "The :attribute is required.",
                "family.required" => "The :attribute is required.",
                "diet.required" => "The :attribute is required.",
                "description.required" => "The :attribute is required.",
                "n_spotted.required" => "The :attribute is required.",
                "maxSize.required" => "The :attribute is required.",
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
            $photo = $request['photo']->store('posts', 'public');
            $oldImage = $insect->photo;
            if ($oldImage != null) {
                Storage::disk('public')->delete($oldImage);
            }
        } else {
            $photo = $insect->photo;
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
        $insect->photo = $photo;
        $insect->save();

        return redirect()->route('home');

    }

}