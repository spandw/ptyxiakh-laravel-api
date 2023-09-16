<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\ParkingSpot;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ApiReservationsController extends Controller
{

    public function getSpotReservationsDates($id)
    {
        // Apply filters based on request parameters
        $reservations = Reservation::query()
            ->where('parking_spot_id', $id)
            ->select('start_date', 'end_date')
            ->get();

        if (sizeof($reservations) === 0) {
            return response()->json([
                'message' => "There are no reservations for this spot."
            ], 404);
        };
        $formattedDates = $this->formatDateRanges($reservations);

        return response()->json($formattedDates);
        // return response()->json([
        //     'message' => "Reservetion on this spot",
        //     'reservations' => $reservations,
        // ], 200);
    }

    private function formatDateRanges($dateRanges)
    {
        $formattedDates = [];

        foreach ($dateRanges as $range) {
            $startDate = Carbon::parse($range['start_date']);
            $endDate = Carbon::parse($range['end_date']);

            while ($startDate <= $endDate) {
                // Format the date as "YYYY/MM/DD" (date only)
                $formattedDates[] = $startDate->format('Y/m/d');
                $startDate->addDay();
            }
        }

        return $formattedDates;
    }

    public function checkAvailability(Request $request)
    {
        // $date1 = Carbon::parse($check_start_date);
        // $date2 = Carbon::parse($check_end_date);
        // $daysDifference = $date2->diffInDays($date1);
        // echo $daysDifference;

        $check_start_date = $request->start_date;
        $check_end_date = $request->end_date;

        $reservations = Reservation::checkDates($check_start_date, $check_end_date)->pluck('parking_spot_id');

        $available_spots = ParkingSpot::whereNotIn('id', $reservations)
            ->get();

        return response()->json([
            'message' => "Reservations on the dates given.",
            'reservations' => $reservations,
            'available_spots' => $available_spots
        ], 200);
    }
}
