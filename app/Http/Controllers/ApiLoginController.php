<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;


class ApiLoginController extends Controller
{
    public function register(Request $request)
    {

        $data = $request->validate([
            'username' => ['required', 'string'],
            'name' => ['required', 'string'],
            'last_name' => ['required', 'string'],
            'email' => ['required', 'email', 'string'],
            'password' => [
                'required',
                'confirmed',
                //Password::min(8)->mixedCase()->numbers()->symbols()
            ]
        ]);

        $user = User::create([
            'username' => $data['username'],
            'name' => $data['name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'credits' => 5,
            'password' => bcrypt($data['password'])
        ]);
        $token = $user->createToken('myapptoken')->plainTextToken;

        return response()->json([
            'success' => true,
            'user' => $user,
            'token' => $token
        ], 200);
    }

    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        /// Check email
        $user = User::where('email', $credentials['email'])->first();

        // Check password
        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Bad creds'
            ], 401);
        }

        $token = $user->createToken('myapptoken')->plainTextToken;
        return response()->json([
            'success' => true,
            'user' => $user,
            'token' => $token
        ], 201);
    }

    public function logout(Request $request)
    {
        auth()->user()->tokens()->delete();
        //uth::logout();
        //$request->session()->invalidate();
        return response()->json([
            'success' => true,
        ]);
    }

    public function getCurrentUser()
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json([
                'message' => 'You are not Logged In'
            ], 401);
        }
        $user->loadCount('parkingSpots');
        return response()->json($user);
    }

    public function authCheck()
    {
        if (Auth::check()) {
            return response()->json([
                'success' => true,
                'message' => 'Authenticated'
            ], 201);
        }

        return response()->json([
            'success' => false,
            'message' => 'Not Authenticated'
        ], 401);
    }
}
