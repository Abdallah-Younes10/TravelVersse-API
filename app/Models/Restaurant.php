<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Restaurant extends Model
{
    use HasFactory;
    protected $fillable = [
    'name_en', 'name_ar',
    'description_en', 'description_ar',
    'location', 'booking_link',
    'rate', 'property_type', 'cuisine', 'features',
];
protected $casts = [
    'features' => 'array',
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
