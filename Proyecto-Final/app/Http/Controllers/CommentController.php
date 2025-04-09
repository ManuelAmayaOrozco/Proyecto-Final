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


class CommentController extends Controller
{
    
    public function showRegisterComment() {
        return view('user_views.insertComments');
    }

    public function doRegisterComment($id, Request $request) {
    
        // VALIDAR DATOS DE ENTRADA.
        $validator = Validator::make(
            $request->all(),
            [
                "comment"=>"required"
            ],[
                "comment.required" => "The :attribute is required."
            ]
        );
    
        // SI LOS DATOS SON INVÁLIDOS, DEVOLVER A LA PÁGINA ANTERIOR E IMPRIMIR LOS ERRORES DE VALIDACIÓN
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        // SI EL USUARIO NO EXISTE, DEVOLVER A LA PÁGINA ANTERIOR E IMPRIMIR LOS ERRORES DE VALIDACIÓN
        $user = Auth::user();
        if(!$user) {
            $validator->errors()->add('credentials', 'This user does not exist, use a different ID.');
            return redirect()->route('comment.showRegisterComment')->withErrors($validator)->withInput();
        }

        // SI LOS DATOS SON VÁLIDOS (SI EL REGISTRO SE HA REALIZADO CORRECTAMENTE) CARGAR LA VIEW
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