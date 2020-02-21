<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function getUserRoles()
    {
        $user = Auth::user();
        $roles = $user->roles()->get();
        return response()->json(json_encode($roles));
    }
}
