<?php

declare(strict_types = 1);

namespace App\Services;

use App\Exceptions\AvatarDeleteException;
use App\Models\UserModel;
use App\Repositories\UserRepository;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Symfony\Component\HttpFoundation\File\Exception\UploadException;

class AvatarManagementService
{
    protected UserModel $user;
    protected UserRepository $repository;

    public function __construct(UserModel $user)
    {
        $this->user = $user;
        $this->repository = new UserRepository($this->user);
    }

    /**
     * Gets user's avatar and returns it.
     */
    public function getAvatar(): string
    {
        $filename = $this->user['avatar'] ?? 'default_avatar.jpg';
        $avatar = Storage::disk('user_avatars')->get($filename);

        return base64_encode($avatar);
    }

    /**
     * Uploads avatar to storage.
     */
    public function uploadAvatar(UploadedFile $avatar): void
    {
        $filename = $avatar->store('', 'user_avatars');

        if (!$filename) {
            throw new UploadException("Avatar wasn't uploaded.");
        }

        $this->resizeImg($avatar, $filename);
        $this->saveAvatarNameInDB($filename);
    }

    /**
     * Deletes previous avatar if exists.
     */
    public function deleteAvatar(): void
    {
        $avatar = $this->user['avatar'];

        if (!$avatar) {
            return;
        }

        $success = Storage::disk('user_avatars')->delete($avatar);

        if (!$success) {
            throw new AvatarDeleteException();
        }

        $this->deleteAvatarNameFromDB();
    }

    /**
     * Saves avatar's filename to database.
     */
    private function saveAvatarNameInDB(string $filename): void
    {
        $this->user['avatar'] = $filename;
        $this->repository->update($this->user);
    }

    /**
     * Deletes avatar's filename from database.
     */
    private function deleteAvatarNameFromDB(): void
    {
        $this->user['avatar'] = null;
        $this->repository->update($this->user);
    }

    /**
     * Resizes given image and overwrites it.
     */
    private function resizeImg(UploadedFile $avatar, string $filename): void
    {
        $path = Storage::disk('user_avatars')->path($filename);
        Image::make($avatar)->resize(81, 81)->save($path);
    }
}
