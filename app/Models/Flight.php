<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Flight extends Model
{
    use HasFactory;

    protected $fillable = [
        'style', 'from_location', 'departure_time', 'to_location', 'arrival_time',
        'return_from', 'return_departure_time', 'return_to', 'return_arrival_time',
        'stops_count', 'stop_locations', 'duration', 'price', 'rate', 'booking_link',
    ];

    protected $casts = [
        'stop_locations' => 'array',
        'departure_time' => 'datetime',
        'arrival_time' => 'datetime',
        'return_departure_time' => 'datetime',
        'return_arrival_time' => 'datetime',
    ];
}
