<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Notifications\ResetPasswordNotification;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens,HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
    'name', 'email', 'password', 'user_type'
];


    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    public function favorites()
{
    return $this->hasMany(Favorite::class);
}

public function carts()
{
    return $this->hasMany(Cart::class);
}

public function trips()
{
    return $this->hasMany(Trip::class, 'guide_id'); // إذا كان المستخدم هو مرشد سياحي
}
public function reviews()
{
    return $this->hasMany(Review::class);
}
public function reservations()
{
    return $this->hasMany(Reservation::class);
}
public function sendPasswordResetNotification($token)
{
    $this->notify(new ResetPasswordNotification($token));
}
// app/Models/User.php
public function image()
{
    return $this->morphOne(\App\Models\Image::class, 'imageable');
}

}
