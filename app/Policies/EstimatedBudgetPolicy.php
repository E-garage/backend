<?php

namespace App\Policies;

use App\Models\EstimatedBudget;
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
    public function view(UserModel $userModel, EstimatedBudget $budget)
    {
        $car = $budget->car;

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
    public function update(UserModel $userModel, EstimatedBudget $budget)
    {
        $car = $budget->car;

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
    public function delete(UserModel $userModel, EstimatedBudget $budget)
    {
        return $userModel->id === $budget->car->owner_id;
    }
}
