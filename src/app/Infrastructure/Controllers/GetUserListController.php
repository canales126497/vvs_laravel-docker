<?php

namespace App\Infrastructure\Controllers;

use App\Application\UserDataSource\UserDataSource;
use App\Infrastructure\Persistence\FileUserDataSource;
use Barryvdh\Debugbar\Controllers\BaseController;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class GetUserListController extends BaseController
{
    private FileUserDataSource $fileUserDataSource;
    public function __construct(FileUserDataSource $fileUserDataSource)
    {
        $this->fileUserDataSource = $fileUserDataSource;
    }
    public function __invoke(): JsonResponse
    {
        $users = $this->fileUserDataSource->getAll();

        $users_ids = array_map(function ($user){
            return $user->getId();
        }, $users);

        return response()->json([
            'status' => 'Ok',
            'user_list' => $users_ids,
        ], Response::HTTP_OK);
    }
}
