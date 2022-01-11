<?php

namespace App\Policies;

use App\Models\Family;
use App\Models\UserModel;
use Illuminate\Auth\Access\HandlesAuthorization;

class FamilyPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\UserModel  $userModel
     * @param  \App\Models\Family  $family
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(UserModel $userModel, Family $family)
    {
        $isOwner = $userModel->id === $family->owner_id;
        $isMember = $family->members()->where('id', $userModel->id)->get();

        return ($isOwner || $isMember);
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\UserModel  $userModel
     * @param  \App\Models\Family  $family
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(UserModel $userModel, Family $family)
    {
        return $userModel->id === $family->owner_id;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\UserModel  $userModel
     * @param  \App\Models\Family  $family
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(UserModel $userModel, Family $family)
    {
        return $userModel->id === $family->owner_id;
    }
}
