<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class UserController extends Controller
{

    public function view($id = null)
    {

        $user = $id ? User::findOrFail($id) : \Auth::user();
        return view('users.view', compact('user'));
    }
}
