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
            'city' => ['required','string'],
            'address' => ['required','string'],
            'postal_code' => ['required','integer','min:0'],
            'vehicle_type' => 'in:motorbike,car,suv,truck',
            
        ]);
        
        $parkingSpot = ParkingSpot::create([
            
            'city' => $data['city'],
            'address' => $data['address'],
            'postal_code' => $data['postal_code'],
            'vehicle_type' => $data['vehicle_type'],
            'user_id' => $request->user()->id
        ]);
        //$token = $user->createToken('main')->plainTextToken;
        
        return response()->json([
            'message'=>'Created Spot succefully',
            'parking_spot' => $parkingSpot

        ],200);
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
            'city' => ['required','string'],
            'address' => ['required','string'],
            'postal_code' => ['required','integer','min:0'],
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
            $parkingSpot->postal_code = $data['postal_code'];
            $parkingSpot->vehicle_type = $data['vehicle_type'];
            $parkingSpot->save();

            return response()->json([
                'parking_spot' => $parkingSpot
            ],200);
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
        ],200);
    }
}
