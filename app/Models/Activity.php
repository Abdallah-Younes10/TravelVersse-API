<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    use HasFactory;
    protected $fillable = [
  'name_en', 'name_ar', 'description_en', 'description_ar',
  'price', 'rate', 'duration', 'type', 'location',
  'booking_link', 'live_guide', 'guide_languages', 'start_time'
];

protected $casts = [
        'start_time' => 'array',
        'guide_languages' => 'array',
        'live_guide' => 'boolean',
    ];


    public function images()
    {
        return $this->morphMany(Image::class, 'imageable');
    }
    public function favorites()
{
    return $this->morphMany(Favorite::class, 'favorable');
}
public function carts()
{
    return $this->morphMany(Cart::class, 'cartable');
}
public function reviews()
{
    return $this->morphMany(Review::class, 'reviewable');
}
public function reservations()
{
    return $this->morphMany(Reservation::class, 'reservable');
}

}
