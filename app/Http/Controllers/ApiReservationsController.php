<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\ParkingSpot;
use App\Models\Reservation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ApiReservationsController extends Controller
{

    public function getSpotReservationsDates($id)
    {
        $reservations = Reservation::query()
            ->where('parking_spot_id', $id)
            ->select('start_date', 'end_date')
            ->get();

        $formattedDates = $this->formatDateRanges($reservations);

        return response()->json($formattedDates);
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

    public function createReservation(Request $request)
    {
        $check_start_date = $request->start_date;
        $check_end_date = $request->end_date;

        if (empty($check_end_date)) {

            $check_start_date = $request->date;
            $check_end_date = $request->date;
        }

        //dates se int
        $int_start_date = Carbon::parse($check_start_date);
        $int_end_date = Carbon::parse($check_end_date);


        $daysDifference = $int_end_date->diffInDays($int_start_date);
        if($int_end_date == $int_start_date){
            $daysDifference = 1;
        }

        $user_id = auth('sanctum')->user()->id;
        $reservee = User::findOrFail($user_id);

        $isNotAvailable = Reservation::checkDates($check_start_date, $check_end_date)
            ->where('parking_spot_id', $request->parking_spot_id)->exists();
        if ($isNotAvailable) {
            return response()->json([
                'message' => "There is already a reservation on those dates."
            ], 422);
        }

        $reserveeCredits = $reservee->credits;
        if ($reserveeCredits < $daysDifference) {
            return response()->json([
                'message' => "You have insufficient amount of credits",
                'Your Credits' => $reserveeCredits,
                'days Difference' => $daysDifference
            ], 422);
        }
        $parkingSpot = ParkingSpot::find($request->parking_spot_id);
        $owner_id = $parkingSpot->user_id;

        $owner = User::find($owner_id);
        $ownerCredits = $owner->credits + $daysDifference;
        $owner->update(['credits' => $ownerCredits]);

        $reserveeCredits = $reserveeCredits - $daysDifference;
        User::where('id', $user_id)->update(['credits' => $reserveeCredits]);

        $reservation = Reservation::create([
            'user_id' => $user_id,
            'parking_spot_id' => $request->parking_spot_id,
            'start_date' => $check_start_date,
            'end_date' => $check_end_date,
        ]);

        return response()->json([
            'message' => "Reservation created successfully",
            'reservation' => $reservation,
        ], 200);
    }

    public function destroy($id)
    {
        $reservation = Reservation::find($id);
        $reservation->delete();

        return response()->json([
            'message' => "Reservation was cancelled!"
        ], 200);
    }
}
