<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use App\Http\Middleware\AuthenticationCheck;
use App\Models\ParkingSpot;

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

    public function getUserParkingSpots($id)
    {
        $userParkingSpots = ParkingSpot::where('user_id', $id)->get();
        if ($userParkingSpots->isEmpty()) {
            return response()->json([
                'message' => 'There are no parking spots of this user'
            ], 404);
        }
        return response()->json($userParkingSpots, 200);
    }
}
