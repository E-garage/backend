<?php

namespace App\Policies;

use App\Models\Car;
use App\Models\UserModel;
use Illuminate\Auth\Access\HandlesAuthorization;

class EstimatedBudgetPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the model.
     *
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(UserModel $userModel, Car $car)
    {
        $isCarOwner = $userModel->id === $car->owner_id;

        if(!$isCarOwner) {
            $family = $car->family;
            $isFamilyOwner = $family->members()->where('id', $userModel->id)->exists();
            $isFamilyMember = $family->owner->id === $userModel->id;
        }

        return ($isCarOwner || $isFamilyOwner || $isFamilyMember);
    }

    /**
     * Determine whether the user can update the model.
     *
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(UserModel $userModel, Car $car)
    {
        $isCarOwner = $userModel->id === $car->owner_id;

        if(!$isCarOwner) {
            $family = $car->family;
            $isFamilyOwner = $family->members()->where('id', $userModel->id)->exists();
            $isFamilyMember = $family->owner->id === $userModel->id;
        }

        return ($isCarOwner || $isFamilyOwner || $isFamilyMember);
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\UserModel  $userModel
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(UserModel $userModel, Car $car)
    {
        return $userModel->id === $car->owner_id;
    }
}
