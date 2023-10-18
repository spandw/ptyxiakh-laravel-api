<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use App\Http\Middleware\AuthenticationCheck;
use App\Models\ParkingSpot;
use App\Models\Reservation;
use Carbon\Carbon;

class ApiUserController extends Controller
{

    // public function show($id)
    // {
    //     return view('user.profile', [
    //         'user' => User::findOrFail($id)
    //     ]);
    // }
    public function update(Request $request)
    {
        $user_id = auth('sanctum')->user()->id;

        $data = $request->validate([
            'username' => ['required', 'string'],
            'name' => ['required', 'string'],
            'last_name' => ['required', 'string'],
            'email' => ['required', 'email', 'string'],
        ]);

        $user = User::find($user_id);
        $user->name = $data['name'];
        $user->last_name = $data['last_name'];
        $user->username = $data['username'];
        $user->email = $data['email'];
        $user->update();

        return response()->json([
            'message' => 'User updated!',
            'success' => true,
            'user' => $user,
            //'token' => $token
        ], 200);
    }
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

    public function getUserReservations()
    {
        $user_id = auth('sanctum')->user()->id;
        $userReservations = Reservation::where('user_id', $user_id)->with('parkingspot')->get();
        // if ($userReservations->isEmpty()) {
        //     return response()->json([
        //         'message' => 'There are no reservations of this user'
        //     ], 422);
        // }
        return response()->json($userReservations, 200);
    }
}
