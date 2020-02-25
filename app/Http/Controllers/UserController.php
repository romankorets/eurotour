<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function getUserRoles()
    {
        return Auth::user()->roles()->get();
    }
}
