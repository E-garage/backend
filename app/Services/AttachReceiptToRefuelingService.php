<?php


namespace App\Services;


use App\Models\Refueling;
use Illuminate\Http\UploadedFile;
use Image;
use Storage;
use Symfony\Component\HttpFoundation\File\Exception\UploadException;

class AttachReceiptToRefuelingService
{
    protected Refueling $refueling;
    protected UploadedFile $receipt;
    protected RefuelingReceiptDeletionService $service;

    public function __construct(Refueling $refueling, UploadedFile $receipt)
        {
            $this->refueling = $refueling;
            $this->receipt = $receipt;
            $this->service = new RefuelingReceiptDeletionService();
        }

    /**
     * @throws \App\Exceptions\CarsThumbnailNotRemovedFromStorageException
     */
    public function attachReceipt(): Refueling
        {
            $filename = $this->refueling['receipt'];
            $this->service->deleteReceipt($filename);

            $filename = $this->storeReceipt();

            $this->resizeReceipt($filename);
            $this->refueling['receipt'] = $filename;

            return $this->refueling;
        }

    /**
     * @throws UploadException
     */
    private function storeReceipt(): string
        {
            $filename = $this->receipt->store('', 'refueling_receipt');

            if (!$filename) {
                throw new UploadException("Receipt wasn't uploaded");
            }

            return $filename;
        }

    private function resizeReceipt(string $filename): void
        {
            $path = Storage::disk('refueling_receipt')->path($filename);
            Image::make($this->receipt)->resize(370, 192)->save($path);
        }
}
