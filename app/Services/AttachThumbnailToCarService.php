<?php

declare(strict_types = 1);

namespace App\Services;

use App\Exceptions\CarsThumbnailNotRemovedFromStorageException;
use App\Models\Car;
use Illuminate\Http\UploadedFile;
use Image;
use Storage;
use Symfony\Component\HttpFoundation\File\Exception\UploadException;

class AttachThumbnailToCarService
{
    protected Car $car;
    protected UploadedFile $thumbnail;
    protected CarThumbnailDeletionService $service;

    public function __construct(Car $car, UploadedFile $thumbnail)
    {
        $this->car = $car;
        $this->thumbnail = $thumbnail;
        $this->service = new CarThumbnailDeletionService();
    }

    /**
     * @throws CarsThumbnailNotRemovedFromStorageException
     */
    public function attachThumbnail(): Car
    {
        $filename = $this->car['thumbnail'];
        $this->service->deleteThumbnail($filename);

        $filename = $this->storeThumbnail();

        $this->resizeThumbnail($filename);
        $this->car['thumbnail'] = $filename;

        return $this->car;
    }

    /**
     * @throws UploadException
     */
    private function storeThumbnail(): string
    {
        $filename = $this->thumbnail->store('', 'cars_thumbnails');

        if (!$filename) {
            throw new UploadException("Thumbnail wasn't uploaded");
        }

        return $filename;
    }

    private function resizeThumbnail(string $filename): void
    {
        $path = Storage::disk('cars_thumbnails')->path($filename);
        Image::make($this->thumbnail)->resize(370, 192)->save($path);
    }
}
