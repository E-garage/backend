<?php

declare(strict_types = 1);

namespace App\Repositories;

use App\Exceptions\InsuranceNotDeletedException;
use App\Exceptions\InsuranceNotRetrievedFromDatabaseException;
use App\Exceptions\InsuranceNotUpdatedException;
use App\Models\Insurance;
use Throwable;

class InsuranceRepository
{
    protected ?Insurance $insurance;

    public function __construct(Insurance $insurance = null)
    {
        $this->insurance = $insurance;
    }

    /**
     * @throws InsuranceNotRetrievedFromDatabaseException
     */
    public function get(): Insurance
    {
        try {
            return $this->insurance->refresh();
        } catch (Throwable) {
            throw new InsuranceNotRetrievedFromDatabaseException();
        }
    }

    /**
     * @throws InsuranceNotUpdatedException
     */
    public function update(Insurance $insurance): void
    {
        try {
            $insurance->updateOrFail();
        } catch (Throwable) {
            throw new InsuranceNotUpdatedException();
        }
    }

    /**
     * @throws InsuranceNotDeletedException
     */
    public function delete(Insurance $insurance): void
    {
        try {
            $this->update($insurance);
        } catch (Throwable) {
            throw new InsuranceNotDeletedException();
        }
    }
}
