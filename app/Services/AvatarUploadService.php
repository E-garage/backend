<?php

declare(strict_types = 1);

namespace App\Services;

use App\Exceptions\AvatarDeleteException;
use App\Models\UserModel;
use App\Repositories\UserRepository;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Nette\Utils\Random;
use Symfony\Component\HttpFoundation\File\Exception\UploadException;

class AvatarUploadService
{
    protected UserModel $user;
    protected UserRepository $repository;

    public function __construct(UserModel $user)
    {
        $this->user = $user;
        $this->repository = new UserRepository($this->user);
    }

    /**
     * Uploads avatar to storage.
     */
    public function uploadAvatar(UploadedFile $avatar): string
    {
        $avatarNewName = $this->getNewNameForAvatar($avatar);
        $path = $avatar->storeAs('', $avatarNewName, 'user_avatars');

        if (!$path) {
            throw new UploadException("Avatar wasn't uploaded.");
        }

        return $path;
    }

    /**
     * Saves avatar's filename to database.
     */
    public function saveAvatarNameInDB(string $pathToAvatar)
    {
        $this->user['avatar'] = $pathToAvatar;
        $this->repository->update($this->user);
    }

    /**
     * Deletes previous avatar if exists.
     */
    public function deletePreviousAvatar(): void
    {
        $avatar = $this->user['avatar'];

        if (!$avatar) {
            return;
        }

        $success = Storage::disk('user_avatars')->delete($avatar);

        if (!$success) {
            throw new AvatarDeleteException();
        }
    }

    /**
     * Renames avatar's filename.
     */
    private function getNewNameForAvatar(UploadedFile $avatar): string
    {
        $avatarName = time() . Random::generate(20) . '.' . $avatar->getClientOriginalExtension();

        return $avatarName;
    }
}
