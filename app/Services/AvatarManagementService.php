<?php

declare(strict_types = 1);

namespace App\Services;

use App\Exceptions\AvatarDeleteException;
use App\Exceptions\UserNotUpdatedException;
use App\Models\UserModel;
use App\Repositories\UserRepository;
use Auth;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Symfony\Component\HttpFoundation\File\Exception\UploadException;

class AvatarManagementService
{
    protected UserRepository $repository;

    public function __construct()
    {
        $this->repository = new UserRepository();
    }

    /**
     * @throws FileNotFoundException
     */
    public function getAvatar(): string
    {
        $user = Auth::user();
        $filename = $user['avatar'] ?? 'default_avatar.jpg';
        $avatar = Storage::disk('user_avatars')->get($filename);

        return base64_encode($avatar);
    }

    /**
     * @throws UserNotUpdatedException
     * @throws UploadException
     */
    public function uploadAvatar(UploadedFile $avatar): void
    {
        $user = Auth::user();
        $filename = $avatar->store('', 'user_avatars');

        if (!$filename) {
            throw new UploadException("Avatar wasn't uploaded.");
        }

        $this->resizeImg($avatar, $filename);
        $this->saveAvatarNameInDB($user, $filename); //@phpstan-ignore-line
    }

    /**
     * @throws AvatarDeleteException
     * @throws UserNotUpdatedException
     */
    public function deleteAvatar(): void
    {
        $user = Auth::user();
        $avatar = $user['avatar'];

        if (!$avatar) {
            return;
        }

        $success = Storage::disk('user_avatars')->delete($avatar);

        if (!$success) {
            throw new AvatarDeleteException();
        }

        $this->deleteAvatarNameFromDB($user); //@phpstan-ignore-line
    }

    /**
     * @throws UserNotUpdatedException
     */
    private function saveAvatarNameInDB(UserModel $user, string $filename): void
    {
        $user['avatar'] = $filename;
        $this->repository->update($user);
    }

    /**
     * @throws UserNotUpdatedException
     */
    private function deleteAvatarNameFromDB(UserModel $user): void
    {
        $user['avatar'] = null;
        $this->repository->update($user);
    }

    private function resizeImg(UploadedFile $avatar, string $filename): void
    {
        $path = Storage::disk('user_avatars')->path($filename);
        Image::make($avatar)->resize(81, 81)->save($path);
    }
}
