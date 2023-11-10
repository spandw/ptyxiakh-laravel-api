<?php

namespace App\Http\Controllers;

use App\Models\ParkingSpot;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Storage;

class ApiParkingController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'city' => ['required', 'string'],
            'address' => ['required', 'string'],
            'title' => ['required', 'string'],
            'description' => ['required', 'string'],
            'type' => 'in:motorbike,car,suv,truck',
            'photo' => ['string'],
        ]);

        $parkingSpot = ParkingSpot::create([

            'city' => $data['city'],
            'address' => $data['address'],
            'title' => $data['title'],
            'description' => $data['description'],
            'vehicle_type' => $data['type'],
            'user_id' => $request->user()->id
        ]);

        $base64_image = $data['photo'];

        if (preg_match('/^data:image\/(\w+);base64,/', $base64_image)) {
            $data = substr($base64_image, strpos($base64_image, ',') + 1);

            $data = base64_decode($data);
            Storage::disk('public')->put("/parking-spots/$parkingSpot->id.png", $data);
        }

        return response()->json([
            'message' => 'Created Spot succefully',
            'parking_spot' => $parkingSpot

        ], 200);
    }

    public function update(Request $request, $id)
    {

        $data = $request->validate([
            'city' => ['required', 'string'],
            'address' => ['required', 'string'],
            'title' => ['required', 'string'],
            'description' => ['required', 'string'],
            'vehicle_type' => 'in:motorbike,car,suv,truck',
            'photo' => ['string'],

        ]);

        $parkingSpot = ParkingSpot::find($id);
        if (!$parkingSpot) {
            return response()->json([
                'message' => 'This Parking Spot Doesn\'t exist'
            ], 404);
        }

        $parkingSpot->city = $data['city'];
        $parkingSpot->address = $data['address'];
        $parkingSpot->title = $data['title'];
        $parkingSpot->description = $data['description'];
        $parkingSpot->vehicle_type = $data['vehicle_type'];

        $base64_image = $data['photo'];

        if (preg_match('/^data:image\/(\w+);base64,/', $base64_image)) {
            $data = substr($base64_image, strpos($base64_image, ',') + 1);

            $data = base64_decode($data);
            Storage::disk('public')->put("/parking-spots/$parkingSpot->id.png", $data);
        }
        $parkingSpot->save();

        return response()->json([
            'parking_spot' => $parkingSpot
        ], 200);
    }
    public function destroy($id)
    {
        $cannotDelete = Reservation::where('parking_spot_id', $id)->exists();
        if($cannotDelete){
            return response()->json([
                'message' => "Cannot delete parking spot beacause it has active reservations."
            ], 422);
        }
        $parkingSpot = ParkingSpot::find($id);
        $parkingSpot->delete();

        return response()->json([
            'message' => "Parking spot deleted successfully!"
        ], 200);
    }

    public function getFilteredParkingSpots(Request $request)
    {
        $query = ParkingSpot::query();
        // Apply filters based on request parameters
        if ($request->has('vehicle_type')) {
            $query->where('vehicle_type', $request->input('vehicle_type'));
        }
        if ($request->has('city')) {
            $query->where('city', $request->input('city'));
        }

        $parkingSpots = $query->get();
        return response()->json($parkingSpots);
        if (!$parkingSpots) {
            return response()->json([
                'message' => 'There are no parking spots yet'
            ], 404);
        }
        return response()->json($parkingSpots, 200);
    }

    public function getParkingSpotById($id)
    {
        $parkingSpot = ParkingSpot::find($id);
        if (!$parkingSpot) {
            return response()->json([
                'message' => 'There are no parking spots yet'
            ], 404);
        }
        return response()->json($parkingSpot, 200);
    }

    public function getDistinctCities()
    {
        $cities = ParkingSpot::distinct()->pluck('city');
        if (!$cities) {
            return response()->json([
                'message' => 'There are no cities'
            ], 404);
        }
        return response()->json($cities, 200);
    }
}
