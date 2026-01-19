<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class GenericPolicy
{
    protected $allowedToModify = ['admin', 'tour_guide'];

    public function viewAny(?User $user)
    {
        return true; // الكل يقدر يشوف القوائم
    }

    public function view(?User $user, Model $model)
    {
        return true; // الكل يقدر يشوف تفاصيل
    }

    public function create(User $user)
    {
        return in_array($user->user_type, $this->allowedToModify);
    }

    public function update(User $user, Model $model)
    {
        return in_array($user->user_type, $this->allowedToModify);
    }

    public function delete(User $user, Model $model)
    {
        return in_array($user->user_type, $this->allowedToModify);
    }
}
