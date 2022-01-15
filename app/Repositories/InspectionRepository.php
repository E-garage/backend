<?php

declare(strict_types = 1);

namespace App\Repositories;

use App\Exceptions\InspectionNotDeletedException;
use App\Exceptions\InspectionNotRetrievedFromDatabaseException;
use App\Exceptions\InspectionNotUpdatedException;
use App\Models\Inspection;
use Throwable;

class InspectionRepository
{
    protected ?Inspection $inspection;

    public function __construct(Inspection $inspection = null)
    {
        $this->inspection = $inspection;
    }

    /**
     * @throws InspectionNotRetrievedFromDatabaseException
     */
    public function get(): Inspection
    {
        try {
            return $this->inspection->refresh();
        } catch (Throwable) {
            throw new InspectionNotRetrievedFromDatabaseException();
        }
    }

    /**
     * @throws InspectionNotUpdatedException
     */
    public function update(Inspection $inspection): void
    {
        try {
            $inspection->updateOrFail();
        } catch (Throwable) {
            throw new InspectionNotUpdatedException();
        }
    }

    /**
     * @throws InspectionNotDeletedException
     */
    public function delete(Inspection $inspection): void
    {
        try {
            $this->update($inspection);
        } catch (Throwable) {
            throw new InspectionNotDeletedException();
        }
    }
}
