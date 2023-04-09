<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\ParkingSpot;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ApiReservationsController extends Controller
{

    public function store(Request $request)
    {
        //
    }

    public function checkAvailabilityAndMakeReservation(Request $request)
    {
        $start_date = '2023-03-23';
        $end_date = '2023-03-24';

        $available_spots = DB::table('parking_spots')
            ->whereNotIn('id', function ($query) use ($start_date, $end_date) {
                $query->select('parking_spot_id')
                    ->from('reservations')
                    ->where('start_date', '<=', $end_date)
                    ->where('end_date', '>=', $start_date);
            })
            ->get();

        return response()->json([
            'message' => "Available Parking spots",
            'available_spots' => $available_spots
        ], 200);
    }
}
