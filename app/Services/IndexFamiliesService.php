<?php

declare(strict_types = 1);

namespace App\Services;

use App\Repositories\FamilyRepository;
use Auth;
use Illuminate\Database\Eloquent\Collection;

class IndexFamiliesService
{
    protected FamilyRepository $repository;

    public function __construct()
    {
        $this->repository = new FamilyRepository();
    }

    /**
     * @throws AuthorizedUserNotFoundException
     */
    public function index(): Collection
    {
        $user = Auth::user();

        return $this->repository->index($user);
    }
}
