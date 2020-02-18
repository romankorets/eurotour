<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function getUserRoles(User $user)
    {
        $roles = $user->roles()->get();
        return response()->json(json_encode($roles));
    }
}
