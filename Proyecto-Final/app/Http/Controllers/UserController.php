<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Post;
use App\Models\Comment;
use App\Models\Insect;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactMailable;

/**
 * Controlador para la clase User
 */
class UserController extends Controller
{

    /**
     * Función que muestra la vista para hacer login en la aplicación.
     * 
     * @return view La vista del login.
     */
    public function showLogin() {
        return view('user_views.login');
    }

    /**
     * Función que realiza el login de un usuario.
     * 
     * @param request $request Request obtenida del formulario que provee
     * los datos necesarios para realizar el login.
     * @return view La vista principal.
     */
    public function doLogin(Request $request) {

        // VALIDAR DATOS DE ENTRADA
        $validator = Validator::make(
            $request->all(),
            [
                "email" => "required|email:rfc,dns|exists:App\Models\User,email",
                "password" => "required",
                "captcha" => "required|captcha"
            ],[
                "email.required"=> "El email es obligatorio.",
                "email.email"=> "El email ha de tener el formato correcto.",
                "email.exists"=> "Ese email no está registrado.",
                "password.required"=> "La contraseña es obligatoria.",
                "captcha.required" => "El captcha es obligatorio.",
                "captcha.captcha" => "El captcha es inválido.",
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
            return redirect()->route('login')->withErrors($validator)->withInput();
        }

        // SI LOS DATOS SON VÁLIDOS (SI EL LOGIN ES CORRECTO) CARGAR LA VISTA.
        $credentials = [
            'email' => $user->email,
            'password' => $userPassword,
        ];
        if (Auth::attempt($credentials)) { // Auth::attempt crea una session en la BD con las credenciales
            $request->session()->regenerate();

            return redirect()->route('home');

        }
        
    }

    /**
     * Función que muestra la vista para registrar un usuario.
     * 
     * @return view La vista para registrar un usuario.
     */
    public function showRegister() {
        return view('user_views.register');
    }

    /**
     * Función que registra un nuevo usuario en la base de datos.
     * 
     * @param request $request Request obtenida del formulario que provee
     * los datos necesarios para crear el usuario.
     * @return view La vista para realizar el login.
     */
    public function doRegister(Request $request) {

        // VALIDAR DATOS DE ENTRADA.
        $validator = Validator::make(
            $request->all(),
            [
                "name"=>"required|string|max:20|unique:App\Models\User,name",
                "email"=> "required|email:rfc,dns|unique:App\Models\User,email",
                "photo" => "image|mimes:jpeg,png,jpg|max:2048",
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
                "photo.image" => "La foto ha de ser una imagen.",
                "photo.mimes" => "La foto ha de ser jpg/png/jpg.",
                "photo.max" => "La foto no puede ser mayor de 2048px.",
                "password.required" => "La contraseña es obligatoria.",
                "password.min" => "La contraseña debe contener 5 carácteres mínimo.",
                "password.max" => "La contraseña debe contener 20 carácteres máximo.",
                "password.regex" => "La contraseña debe contener una minúscula, una mayúscula y un dígito",
                "password_repeat.required" => "La contraseña repetida es obligatoria.",
                "password_repeat.same" => "La contraseña repetida ha de ser igual a la contraseña original.",
            ]
        );

        // SI LOS DATOS SON INVÁLIDOS, DEVOLVER A LA PÁGINA ANTERIOR E IMPRIMIR LOS ERRORES DE VALIDACIÓN
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        // SUBIR IMAGEN A IMGBB SI EXISTE
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

        // SI LOS DATOS SON VÁLIDOS (SI EL REGISTRO SE HA REALIZADO CORRECTAMENTE) CARGAR LA VIEW.
        $datosUsuario = $request->all();
        $user = new User();
        $user->name = $datosUsuario['name'];
        $user->email = $datosUsuario['email'];
        $user->password = $datosUsuario['password'];
        $user->photo = $photoUrl;
        $user->save();

        Auth::login($user);

        $request->user()->sendEmailVerificationNotification();

        return redirect()->route('verification.notice');
    }

    /**
     * Función que muestra la vista para verificar el correo de un usuario.
     * 
     * @return view La vista para verificar el correo de un usuario.
     */
    public function showVerification() {
        return view('user_views.mailVerification');
    }

    /**
     * Función que muestra la vista del perfil del usuario actual.
     * 
     * @return view La vista del perfil del usuario actual.
     */
    public function showProfile() {
        $current_user = Auth::user();
        return view('user_views.profile', compact('current_user'));
    }

    /**
     * Función que muestra la vista del menú de administración de usuarios.
     * 
     * @return view La vista del menú de administración de usuarios.
     */
    public function showAdminMenu() {
        $users = User::all();
        $current_user = Auth::user();
        return view('user_views.adminMenu', compact('users', 'current_user'));
    }

    /**
     * Función que realiza el logout de la sesión del usuario actual.
     * 
     * @param long $id El ID del usuario actual.
     * @return view La vista del login.
     */
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

    /**
     * Función para eliminar un usuario específico de la base de datos.
     * 
     * @param long $id El ID del usuario que deseamos eliminar.
     * @return view La vista principal.
     */
    public function deleteUser($id) {
        $validator = Validator::make(
            ['id' => $id],
            [
                'id' => 'required|exists:users,id'
            ]
        );

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        try {
            DB::transaction(function () use ($id) {
                $user = User::findOrFail($id);

                $user->posts()->delete();
                $user->comments()->delete();
                $user->insects()->delete();

                $user->delete();
            });

            return redirect()->route('home');

        } catch (\Exception $e) {
            // Devuelve el error detallado en JSON para debugging
            return response()->json([
                'error' => true,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ], 500);
        }
    }

    /**
     * Función que muestra la vista para actualizar un usuario.
     * 
     * @param long El ID del usuario que se va a actualizar.
     * @return view La vista para actualizar un usuario.
     */
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

    /**
    * Función que actualiza un usuario de la base de datos.
    * 
    * @param long $id El ID del usuario que se va a actualizar.
    * @param request $request Request obtenida del formulario que provee
    * los datos necesarios para actualizar el usuario.
    * @return view La vista principal.
    */
    public function updateUser(Request $request, $id) {

        // VALIDAR DATOS DE ENTRADA.
        $validator = Validator::make(
            $request->all(),
            [
                "name"=>"required|string|max:20",
                "email"=> "required|email:rfc,dns",
                "photo" => "image|mimes:jpeg,png,jpg|max:2048",
            ],[
                "name.required" => "The :attribute is required.",
                "name.string" => "The :attribute must be string.",
                "name.max" => "The :attribute can't be longer than 20 characters.",
                "email.required" => "The :attribute is required.",
                "email.email" => "The :attribute must have the correct format.",
                "photo.image" => "La foto ha de ser una imagen.",
                "photo.mimes" => "La foto ha de ser jpg/png/jpg.",
                "photo.max" => "La foto no puede ser mayor de 2048px."
            ]
        );

        // SI LOS DATOS SON INVÁLIDOS, DEVOLVER A LA PÁGINA ANTERIOR E IMPRIMIR LOS ERRORES DE VALIDACIÓN
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        $user = Auth::user();

        // ACTUALIZAMOS LA FOTO SI SE SUBE UNA NUEVA
        $photoUrl = $user->photo;
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

        // SI LOS DATOS SON VÁLIDOS (SI EL REGISTRO SE HA REALIZADO CORRECTAMENTE) CARGAR LA VIEW.
        $datosUsuario = $request->all();
        $user->name = $datosUsuario['name'];
        $user->email = $datosUsuario['email'];
        $user->photo = $photoUrl;
        $user->save();

        return redirect()->route('home');
    }

    /**
     * Función que muestra la vista para actualizar la contraseña de un usuario.
     * 
     * @param long El ID del usuario que se va a actualizar.
     * @return view La vista para actualizar la contraseña de un usuario.
     */
    public function showUpdatePassword($id) {
        $user = null;

        $users = User::all();

        foreach ($users as $usero) {

            if ($usero->id == $id) {

                $user = $usero;

            }

        }

        return view('user_views.updatePassword', compact('user'));
    }

    /**
    * Función que actualiza la contraseña de un usuario de la base de datos.
    * 
    * @param long $id El ID del usuario que se va a actualizar.
    * @param request $request Request obtenida del formulario que provee
    * los datos necesarios para actualizar el usuario.
    * @return view La vista principal.
    */
    public function updatePassword(Request $request, $id) {

        // VALIDAR DATOS DE ENTRADA.
        $validator = Validator::make(
            $request->all(),
            [
                "password" => [
                    "required",
                    "min:5",
                    "max:20",
                    "regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/",
                    Rule::unique('users')->where(fn ($query) => $query->where('id', $id))
                ],
                "password_repeat" => "required|same:password"
            ],
            [
                "password.required" => "La contraseña es obligatoria.",
                "password.min" => "La contraseña debe contener 5 carácteres mínimo.",
                "password.max" => "La contraseña debe contener 20 carácteres máximo.",
                "password.regex" => "La contraseña debe contener al menos una minúscula, una mayúscula y un dígito.",
                "password.unique" => "La contraseña no puede ser igual a la contraseña antigua.",
                "password_repeat.required" => "La contraseña repetida es obligatoria.",
                "password_repeat.same" => "La contraseña repetida ha de ser igual a la contraseña original.",
            ]
        );

        // SI LOS DATOS SON INVÁLIDOS, DEVOLVER A LA PÁGINA ANTERIOR E IMPRIMIR LOS ERRORES DE VALIDACIÓN
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        $user = Auth::user();

        // SI LOS DATOS SON VÁLIDOS (SI EL REGISTRO SE HA REALIZADO CORRECTAMENTE) CARGAR LA VIEW.
        $datosUsuario = $request->all();
        $user->password = $datosUsuario['password'];
        $user->save();

        return redirect()->route('home');

    }

    /**
     * Función que muestra la vista de contactos de la empresa.
     * 
     * @return view La vista de contactos de la empresa.
     */
    public function showContact() {
        return view('user_views.contact');
    }

    /**
     * Función que envia un correo de contacto a la 'empresa'.
     * 
     * @param request $request Request obtenida del formulario que provee
     * los datos necesarios para enviar el correo.
     * @return view La vista de contacto.
     */
    public function doContact(Request $request) {

        // VALIDAR DATOS DE ENTRADA.
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

        // SI LOS DATOS SON INVÁLIDOS, DEVOLVER A LA PÁGINA ANTERIOR E IMPRIMIR LOS ERRORES DE VALIDACIÓN
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        Mail::to('manuamayaorozco@gmail.com')->send(new ContactMailable($request->all()));

        session()->flash('info', 'Correo enviado con éxito.');

        return redirect()->route('user.showContact');
    }

    /**
     * Función que da permisos de administrador a un usuario.
     * 
     * @param long $id El ID del usuario al que se le concederan los permisos.
     * @return view La vista anterior.
     */
    public function makeAdmin($id) {
    
        $user = User::find($id);

        $user->isAdmin = true;

        $user->save();

        return redirect()->back();

    }

    /**
     * Función que banea un usuario, dejándolo incapaz de acceder a varias funciones.
     * 
     * @param long $id El ID del usuario que será baneado.
     * @return view La vista anterior.
     */
    public function banUser($id) {
    
        $user = User::find($id);

        $user->banned = true;

        $user->save();

        return redirect()->back();

    }

    /**
     * Función que desbanea un usuario.
     * 
     * @param long $id El ID del usuario que será desbaneado.
     * @return view La vista anterior.
     */
    public function unbanUser($id) {
    
        $user = User::find($id);

        $user->banned = false;

        $user->save();

        return redirect()->back();

    }

}
