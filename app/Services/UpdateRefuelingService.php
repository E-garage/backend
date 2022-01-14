<?php


namespace App\Services;


use App\Models\Refueling;
use App\Repositories\RefuelingRepository;

class UpdateRefuelingService
{
    protected Refueling $refueling;
    protected array $data;
    protected RefuelingRepository $repository;

    public function __construct(Refueling $refueling, array $data = [])
    {
        $this->refueling = $refueling;
        $this->data = $data;
        $this->repository = new RefuelingRepository($this->refueling);
    }

    /**
     * @throws \App\Exceptions\RefuelingNotUpdatedException
     */
    public function update(): void
    {
        $this->repository->update($this->data);
    }
}
