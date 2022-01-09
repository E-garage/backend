<?php

declare(strict_types = 1);

namespace App\Repositories;

use App\Exceptions\AuthorizedUserNotFoundException;
use App\Exceptions\FamilyDetailsNotFoundException;
use App\Exceptions\FamilyNotSavedToDatabaseException;
use App\Models\Family;
use App\Models\UserModel;
use Illuminate\Database\Eloquent\Collection;

class FamilyRepository
{
    protected ?Family $family;

    public function __construct(Family $family = null)
    {
        $this->family = $family;
    }

    /**
     * @throws AuthorizedUserNotFoundException
     */
    public function index(UserModel $user): Collection
    {
        try {
            return $user->families()->get();
        } catch (\Throwable) {
            throw new AuthorizedUserNotFoundException();
        }
    }

    /**
     * @throws FamilyDetailsNotFoundException
     */
    public function show(): Collection
    {
        try {
            return $this->family->with('members', 'cars')->get();
        } catch (\Throwable) {
            throw new FamilyDetailsNotFoundException();
        }
    }

    /**
     * @throws FamilyNotSavedToDatabaseException
     */
    public function save(): void
    {
        try {
            $this->family->saveOrFail();
        } catch (\Throwable) {
            throw new FamilyNotSavedToDatabaseException();
        }
    }
}
