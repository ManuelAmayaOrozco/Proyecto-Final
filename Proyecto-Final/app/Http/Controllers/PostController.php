<?php

namespace App\Http\Controllers;

use App\Enums\Status;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{

    public function showPosts() {

        return view('home');

    }

}