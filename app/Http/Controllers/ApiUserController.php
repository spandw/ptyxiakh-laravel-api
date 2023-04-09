<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use App\Http\Middleware\AuthenticationCheck;

class ApiUserController extends Controller
{
    
    // public function show($id)
    // {
    //     return view('user.profile', [
    //         'user' => User::findOrFail($id)
    //     ]);
    // }
    public function getAllUsers()
    {
        $users = User::all();
        if (!$users) {
            return response()->json([
                'message' => 'There are no users yet'
            ], 404);
        }
        return response()->json($users, 200);
    }
    public function getUserById($id)
    {

        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'message' => 'There is not such a user'
            ], 404);
        }

        return response()->json($user, 200);
    }
}
