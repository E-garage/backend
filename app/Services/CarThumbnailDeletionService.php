<?php

declare(strict_types = 1);

namespace App\Services;

use App\Exceptions\CarsThumbnailNotRemovedFromStorageException;
use Storage;

class CarThumbnailDeletionService
{
    /**
     * Removes car's thumbnail from storage if exists.
     *
     * @throws CarsThumbnailNotRemovedFromStorageException
     */
    public function deleteThumbnail(?string $filename): void
    {
        if (!$filename) {
            return;
        }

        $success = Storage::disk('cars_thumbnails')->delete($filename);

        if (!$success) {
            throw new CarsThumbnailNotRemovedFromStorageException();
        }
    }
}
