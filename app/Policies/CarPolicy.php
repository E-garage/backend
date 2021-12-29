<?php

namespace App\Policies;

use App\Models\Car;
use App\Models\UserModel;
use Illuminate\Auth\Access\HandlesAuthorization;

class CarPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\UserModel  $userModel
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(UserModel $userModel)
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\UserModel  $userModel
     * @param  \App\Models\Car  $car
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(UserModel $userModel, Car $car)
    {
        //
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\UserModel  $userModel
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(UserModel $userModel)
    {
        //
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\UserModel  $userModel
     * @param  \App\Models\Car  $car
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(UserModel $userModel, Car $car)
    {
        //
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\UserModel  $userModel
     * @param  \App\Models\Car  $car
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(UserModel $userModel, Car $car)
    {
        return $userModel->id === $car->owner_id;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\UserModel  $userModel
     * @param  \App\Models\Car  $car
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(UserModel $userModel, Car $car)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\UserModel  $userModel
     * @param  \App\Models\Car  $car
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(UserModel $userModel, Car $car)
    {
        //
    }
}
