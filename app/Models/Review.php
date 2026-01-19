<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Review extends Model
{
    protected $fillable = [
        'user_id',
        'rate',
        'comment',
        'reviewable_id',
        'reviewable_type',
    ];

    protected $casts = [
        'rate' => 'integer',
    ];

    // ✅ العلاقة مع الـ User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // ✅ العلاقة المتعددة (Polymorphic)
    public function reviewable(): MorphTo
    {
        return $this->morphTo();
    }
}
