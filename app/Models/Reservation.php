<?php

namespace App\Models;

use App\Models\ParkingSpot;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Reservation extends Model
{
    use HasFactory;

    protected $fillable = [
        "user_id",
        "parking_spot_id",
        "start_date",
        "end_date"
    ];

    protected $casts = [
        "start_date" =>  'datetime',
        "end_date" =>  'datetime'

    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function parkingSpot()
    {
        return $this->belongsTo(ParkingSpot::class);
    }
}
