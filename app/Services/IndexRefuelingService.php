<?php

declare(strict_types = 1);

namespace App\Services;

use App\Repositories\RefuelingRepository;
use Auth;
use Illuminate\Database\Eloquent\Collection;
use Storage;

class IndexRefuelingService
{
    protected RefuelingRepository $repository;

    public function __construct()
    {
        $this->repository = new RefuelingRepository();
    }

    /**
     * @throws \App\Exceptions\AuthorizedUserNotFoundException
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function index(): Collection
    {
        $user = Auth::user();
        $refueling = $this->repository->index($user); //@phpstan-ignore-line

        return $this->getReceiptForRefueling($refueling);
    }

    /**
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    private function getReceiptForRefueling(Collection $refueling): Collection
    {
        foreach ($refueling as $refuel) {
            $filename = $refuel['receipt'];

            if ($filename) {
                $file = Storage::disk('refueling_receipt')->get($filename);
                $refuel['receipt'] = base64_encode($file);
            }
        }

        return $refueling;
    }
}
