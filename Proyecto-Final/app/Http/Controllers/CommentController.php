<?php

namespace App\Http\Controllers;

use App\Enums\Status;
use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/**
 * Controlador para la clase Comment
 */
class CommentController extends Controller
{
    /**
     * Función que muestra la vista para registrar un nuevo comentario.
     * 
     * @return view La vista para registrar un nuevo comentario.
     */
    public function showRegisterComment() {
        return view('user_views.insertComments');
    }

    /**
     * Función que registra un nuevo comentario en la base de datos.
     * 
     * @param long $id El ID del post al que pertenece el comentario.
     * @param request $request Request obtenida del formulario que provee
     * los datos necesarios para crear el comentario.
     * @return view La vista del post al que pertenece el comentario.
     */
    public function doRegisterComment($id, Request $request) {
    
        // VALIDAR DATOS DE ENTRADA.
        $validator = Validator::make(
            $request->all(),
            [
                "comment"=>"required"
            ],[
                "comment.required" => "El comentario es obligatorio."
            ]
        );
    
        // SI LOS DATOS SON INVÁLIDOS, DEVOLVER A LA PÁGINA ANTERIOR E IMPRIMIR LOS ERRORES DE VALIDACIÓN.
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        // SI EL USUARIO NO EXISTE, DEVOLVER A LA PÁGINA ANTERIOR E IMPRIMIR LOS ERRORES DE VALIDACIÓN
        $user = Auth::user();
        if(!$user) {
            $validator->errors()->add('credentials', 'This user does not exist, use a different ID.');
            return redirect()->route('comment.showRegisterComment')->withErrors($validator)->withInput();
        }

        // SI LOS DATOS SON VÁLIDOS (SI EL REGISTRO SE HA REALIZADO CORRECTAMENTE) CARGAR LA VIEW.
        $datosComment = $request->all();
        $comment = new Comment();
        $comment->comment = $datosComment['comment'];
        $comment->publish_date = date('d-m-y h:i:s');
        $comment->user_id = Auth::id();
        $comment->post_id = $id;
        $comment->save();

        return redirect()->route('post.showFullPost', ['id' => $id]);

    }

}