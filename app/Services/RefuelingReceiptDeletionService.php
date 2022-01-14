<?php


namespace App\Services;


use App\Exceptions\RefuelingReceiptNotRemovedFromStorageException;
use Storage;

class RefuelingReceiptDeletionService
{

    /**
     * RefuelingReceiptDeletionService constructor.
     */
    public function deleteReceipt(?string $filename): void
    {
        if (!$filename) {
            return;
        }

        $success = Storage::disk('refueling_receipt')->delete($filename);

        if (!$success) {
            throw new RefuelingReceiptNotRemovedFromStorageException();
        }
    }
}
