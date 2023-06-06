<?php

namespace App\Http\Controllers;

use App\Models\ParkingSpot;
use Illuminate\Http\Request;



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
            'price' => ['required', 'numeric', 'min:0'],
            'type' => 'in:motorbike,car,suv,truck',

        ]);

        $parkingSpot = ParkingSpot::create([

            'city' => $data['city'],
            'address' => $data['address'],
            'title' => $data['title'],
            'description' => $data['description'],
            'price' => $data['price'],
            'vehicle_type' => $data['type'],
            'user_id' => $request->user()->id
        ]);
        //$token = $user->createToken('main')->plainTextToken;

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

    public function getAllParkingSpots()
    {
        $parkingSpots = ParkingSpot::all();
        if (!$parkingSpots) {
            return response()->json([
                'message' => 'There are no parking spots yet'
            ], 404);
        }
        return response()->json($parkingSpots, 200);
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
