<?php

namespace App\Policies;

use App\Models\Insurance;
use App\Models\UserModel;
use Illuminate\Auth\Access\HandlesAuthorization;

class InsurancePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the model.
     *
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(UserModel $userModel, Insurance $insurance)
    {
        $car = $insurance->car;

        if ($userModel->id === $car->owner_id) {
            return true;
        }

        if (!$car->family()->exists()) {
            return false;
        }

        $family = $car->family;
        $isFamilyOwner = $family->members()->where('id', $userModel->id)->exists();
        $isFamilyMember = $family->owner->id === $userModel->id;

        return $isFamilyOwner || $isFamilyMember;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(UserModel $userModel, Insurance $insurance)
    {
        $car = $insurance->car;

        if ($userModel->id === $car->owner_id) {
            return true;
        }

        if (!$car->family()->exists()) {
            return false;
        }

        $family = $car->family;
        $isFamilyOwner = $family->members()->where('id', $userModel->id)->exists();
        $isFamilyMember = $family->owner->id === $userModel->id;

        return $isFamilyOwner || $isFamilyMember;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(UserModel $userModel, Insurance $insurance)
    {
        $car = $insurance->car;

        return $userModel->id === $car->owner_id;
    }
}
