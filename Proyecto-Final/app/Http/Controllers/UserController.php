<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Post;
use App\Models\Comment;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactMailable;

class UserController extends Controller
{
    //Show login form
    public function showLogin() {
        return view('user_views.login'); // CARGA LA VIEW DE LOGIN PARA PODER REALIZAR LOGIN
    }

    //Do login
    public function doLogin(Request $request) {
        // VALIDAR DATOS DE ENTRADA
        $validator = Validator::make(
            $request->all(),
            [
                "email" => "required|email:rfc,dns|exists:App\Models\User,email",
                "password" => "required",
            ]
        );

        // SI LOS DATOS SON INVÁLIDOS, DEVOLVER A LA PÁGINA ANTERIOR E IMPRIMIR LOS ERRORES DE VALIDACIÓN
        if ($validator->fails()) {
            return redirect()->route('login')->withErrors($validator)->withInput();
        }

        // SI EL LOGIN ES INCORRECTO, DEVOLVER A LA PÁGINA ANTERIOR E IMPRIMIR LOS ERRORES DE VALIDACIÓN
        $userEmail = $request->get('email');
        $userPassword = $request->get('password');
        $user = User::where('email', $userEmail)->first();
        if(!password_verify($userPassword, $user->password)) {
            $validator->errors()->add('credentials', 'Credenciales incorrectas');
            return redirect()->route('user_views.login')->withErrors($validator)->withInput();
        }

        // SI LOS DATOS SON VÁLIDOS (SI EL LOGIN ES CORRECTO) CARGAR LA VISTA PRINCIPAL DEL USUARIO.
        // LA VISTA PRINCIPAL DE USUARIO DEBE INCLUIR:
        /*
            -> Un header que contenga el nombre del usuario.
            -> Un botón de logout que redirija a la view de login.

            -> La lista de tareas, tanto pendientes como realizadas, que el usuario tiene asignadas.
            -> Un botón al lado de cada tarea para eliminar la tarea.
            -> Un botón para marcar como hecha la tarea.
        */
        $credentials = [
            'email' => $user->email,
            'password' => $userPassword,
        ];
        if (Auth::attempt($credentials)) { // Auth::attempt crea una session en la BD con las credenciales
            $request->session()->regenerate();

            return redirect()->route('home');

        }
        
    }

    //Show register form
    public function showRegister() {
        return view('user_views.register'); // CARGA LA VIEW DE REGISTER PARA PODER REALIZAR UN ALTA DE USUARIO
    }

    //Do register
    public function doRegister(Request $request) {

        // VALIDAR DATOS DE ENTRADA. LAS REGLAS DE VALIDACIÓN SON LAS SIGUIENTES:
        /*
            -> nombre es obligatorio, debe ser un string y debe ser menor de 20 carácteres
            -> email es obligatorio, debe seguir un formato estándar, debe ser único en la base de datos
            -> password es obligatoria, debe ser mayor de 5 carácteres, menor de 20 carácteres, debe contener una minúscula, una mayúscula y al menos un dígito
            -> password_repeat es obligatoria y debe ser igual a password
        */
        $validator = Validator::make(
            $request->all(),
            [
                "name"=>"required|string|max:20|unique:App\Models\User,name",
                "email"=> "required|email:rfc,dns|unique:App\Models\User,email",
                "password"=>"required|min:5|max:20|regex:/[a-z]/|regex:/[A-Z]/|regex:/[0-9]/",
                "password_repeat"=>"required|same:password"
            ],[
                "name.required"=> "El nombre es obligatorio.",
                "name.string"=> "El nombre ha de ser un String",
                "name.max"=> "El nombre debe contener 20 carácteres como máximo.",
                "name.unique"=> "Ese nombre ya está en uso.",
                "email.required"=> "El email es obligatorio.",
                "email.email"=> "El email ha de tener el formato correcto.",
                "email.unique"=> "Ese email ya está en uso.",
                "password.required" => "La contraseña es obligatoria.",
                "password.min" => "La contraseña debe contener 5 carácteres mínimo.",
                "password.max" => "La contraseña debe contener 20 carácteres máximo.",
                "password.regex" => "La contraseña debe contener una minúscula, una mayúscula y un dígito",
                "password_repeat.required" => "La contraseña repetida es obligatoria.",
                "password_repeat.same" => "La contraseña repetida ha de ser igual a la contraseña original.",
            ]
        );

        if ($request->hasFile('photo')) {
            $photo = $request['photo']->store('profiles', 'public');
        } else {
            $photo = null;
        }

        // SI LOS DATOS SON INVÁLIDOS, DEVOLVER A LA PÁGINA ANTERIOR E IMPRIMIR LOS ERRORES DE VALIDACIÓN
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        // SI LOS DATOS SON VÁLIDOS (SI EL REGISTRO SE HA REALIZADO CORRECTAMENTE) CARGAR LA VIEW DE LOGIN PARA PODER REALIZAR LOGIN
        $datosUsuario = $request->all();
        $user = new User();
        $user->name = $datosUsuario['name'];
        $user->email = $datosUsuario['email'];
        $user->password = $datosUsuario['password'];
        $user->photo = $photo;
        $user->save();

        return view('user_views.login'); // CARGA LA VIEW DE LOGIN PARA PODER REALIZAR LOGIN
    }

    public function showProfile() {
        $current_user = Auth::user();
        return view('user_views.profile', compact('current_user'));
    }

    public function logout($id) {

        $validator = Validator::make(
            ['id' => $id],
            [
                'id' => 'required|exists:App\Models\User,id'
            ]
        );

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        Auth::logout();

        return redirect()->route('login');

    }

    public function delete($id) {

        $validator = Validator::make(
            ['id' => $id],
            [
                'id' => 'required|exists:App\Models\User,id'
            ]
        );

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        $posts = Post::where("belongs_to", $id);
        $posts->delete();

        $comments = Comment::where("user_id", $id);
        $comments->delete();

        $user = User::find($id);

        $image = $user->photo;
        Storage::disk('public')->delete($image);

        $user->delete();

        return redirect()->route('home');

    }

    public function showUpdateUser($id) {

        $user = null;

        $users = User::all();

        foreach ($users as $usero) {

            if ($usero->id == $id) {

                $user = $usero;

            }

        }

        return view('user_views.updateUsers', compact('user'));
    }

    public function updateUser(Request $request, $id) {

        // VALIDAR DATOS DE ENTRADA. LAS REGLAS DE VALIDACIÓN SON LAS SIGUIENTES:
        /*
            -> nombre es obligatorio, debe ser un string y debe ser menor de 20 carácteres
            -> email es obligatorio, debe seguir un formato estándar, debe ser único en la base de datos
            -> password es obligatoria, debe ser mayor de 5 carácteres, menor de 20 carácteres, debe contener una minúscula, una mayúscula y al menos un dígito
            -> password_repeat es obligatoria y debe ser igual a password
        */
        $validator = Validator::make(
            $request->all(),
            [
                "name"=>"required|string|max:20",
                "email"=> "required|email:rfc,dns",
            ],[
                "name.required" => "The :attribute is required.",
                "name.string" => "The :attribute must be string.",
                "name.max" => "The :attribute can't be longer than 20 characters.",
                "email.required" => "The :attribute is required.",
                "email.email" => "The :attribute must have the correct format.",
            ]
        );

        // SI LOS DATOS SON INVÁLIDOS, DEVOLVER A LA PÁGINA ANTERIOR E IMPRIMIR LOS ERRORES DE VALIDACIÓN
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        $user = Auth::user();

        if ($request->hasFile('photo')) {
            $photo = $request['photo']->store('posts', 'public');
            $oldImage = $user->photo;
            if ($oldImage != null) {
                Storage::disk('public')->delete($oldImage);
            }
        } else {
            $photo = $user->photo;
        }

        // SI LOS DATOS SON VÁLIDOS (SI EL REGISTRO SE HA REALIZADO CORRECTAMENTE) CARGAR LA VIEW DE LOGIN PARA PODER REALIZAR LOGIN
        $datosUsuario = $request->all();
        $user->name = $datosUsuario['name'];
        $user->email = $datosUsuario['email'];
        $user->photo = $photo;
        $user->save();

        return redirect()->route('home');
    }

    public function showContact() {
        return view('user_views.contact');
    }

    public function doContact(Request $request) {

        $validator = Validator::make(
            $request->all(),
            [
                "name"=>"required",
                "surnames"=>"required",
                "email"=> "required|email:rfc,dns",
                "phonenumber"=>"required",
                "company"=>"nullable",
                "message"=>"required"
            ],[
                "name.required" => "El nombre es obligatorio.",
                "surnames.required" => "Los apellidos son obligatorios.",
                "email.required" => "El email es obligatorio.",
                "email.email" => "El email debe tener el formato correcto.",
                "phonenumber.required" => "El número de teléfono es obligatorio.",
                "message.required" => "El mensaje es obligatorio."
            ]
        );

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        Mail::to('manuamayaorozco@gmail.com')->send(new ContactMailable($request->all()));

        session()->flash('info', 'Correo enviado con éxito.');

        return redirect()->route('user.showContact');
    }

}
