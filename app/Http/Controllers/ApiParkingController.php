<?php

namespace App\Http\Controllers;

use App\Models\ParkingSpot;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Storage;

class ApiParkingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */


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
        //$token = $user->createToken('main')->plainTextToken;
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

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
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

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $parkingSpot = ParkingSpot::find($id);
        $parkingSpot->delete();

        return response()->json([
            'message' => "Parking Spot Was Deleted Successsfully!!"
        ], 200);
    }

    public function getFilteredParkingSpots(Request $request)
    {
        // $parkingSpots = ParkingSpot::all();
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
