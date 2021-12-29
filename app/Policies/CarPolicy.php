<?php

namespace App\Policies;

use App\Models\Car;
use App\Models\UserModel;
use Illuminate\Auth\Access\HandlesAuthorization;

class CarPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the model.
     *
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(UserModel $userModel, Car $car)
    {
        return $userModel->id === $car->owner_id;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(UserModel $userModel, Car $car)
    {
        return $userModel->id === $car->owner_id;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(UserModel $userModel, Car $car)
    {
        return $userModel->id === $car->owner_id;
    }
}
