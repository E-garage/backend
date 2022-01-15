<?php

namespace App\Policies;

use App\Models\Refueling;
use App\Models\UserModel;
use Illuminate\Auth\Access\HandlesAuthorization;

class RefuelingPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the model.
     *
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(UserModel $userModel, Refueling $refueling)
    {
        return $userModel->id === $refueling->owner_id;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(UserModel $userModel, Refueling $refueling)
    {
        return $userModel->id === $refueling->owner_id;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(UserModel $userModel, Refueling $refueling)
    {
        return $userModel->id === $refueling->owner_id;
    }
}
