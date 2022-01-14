<?php

declare(strict_types = 1);

namespace App\Services;


use App\Models\Refueling;
use App\Repositories\RefuelingRepository;

class DeleteRefuelingService
{
    protected Refueling $refueling;
    protected RefuelingRepository $repository;
    protected RefuelingReceiptDeletionService $service;

    public function __construct(Refueling $refueling)
    {
        $this->refueling = $refueling;
        $this->repository = new RefuelingRepository($this->refueling);
        $this->service = new RefuelingReceiptDeletionService();
    }

    /**
     * @throws \App\Exceptions\CarsThumbnailNotRemovedFromStorageException
     * @throws \App\Exceptions\CarNotDeletedFromDatabaseException
     */
    public function deleteRefueling(): void
    {
        $filename = $this->refueling['receipt'];

        $this->repository->delete();
        $this->service->deleteReceipt($filename);
    }
}
