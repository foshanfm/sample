<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UsersController extends Controller
{
    //11.5
    public function create()
    {
        return view('users.create');
    }

}
