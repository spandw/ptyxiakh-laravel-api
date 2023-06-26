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
        $start_date = Carbon::createFromFormat('d-m-Y', $request->start_date);
        $end_date = Carbon::createFromFormat('d-m-Y', $request->end_date);

        $available_spots = Reservation::query()
            ->whereDate('start_date', '>=', $start_date)
            ->whereDate('end_date', '<=', $end_date)
            ->get();

        return response()->json([
            'message' => "Available Parking spots", 
            'available_spots' => $available_spots
        ], 200);
    }
}
