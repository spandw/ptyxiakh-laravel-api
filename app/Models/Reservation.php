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

    public function scopeCheckDates($query, $check_start_date, $check_end_date)
    {
        if (empty($check_end_date)) {
            
            return $query->where('start_date', '<=', $check_start_date)
                ->where('end_date', '>=', $check_start_date);
        } else {
            return $query->whereBetween('start_date', [$check_start_date, $check_end_date])
                ->orWhereBetween('end_date', [$check_start_date, $check_end_date]);
        }
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function parkingSpot()
    {
        return $this->belongsTo(ParkingSpot::class);
    }
}
