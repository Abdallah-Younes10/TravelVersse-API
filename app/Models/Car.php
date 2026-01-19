<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    use HasFactory;
    protected $fillable = [
    'brand', 'model', 'type', 'year', 'price', 'location',
    'description_en', 'description_ar', 'rate', 'booking_link',
    'seats', 'large_bag', 'small_bag', 'car_specification', 'supplier'
];

    public function images()
    {
        return $this->morphMany(Image::class, 'imageable');
    }

    public function favorites()
    {
        return $this->morphMany(Favorite::class, 'favoritable');
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
