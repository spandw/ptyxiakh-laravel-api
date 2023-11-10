<?php

namespace App\Models;

use App\Models\Reservation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ParkingSpot extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'city',
        'address',
        'title',
        'price',
        'description',
        'vehicle_type'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }
}
