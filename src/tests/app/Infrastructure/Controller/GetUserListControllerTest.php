<?php

namespace Tests\app\Infrastructure\Controller;

use App\Application\UserDataSource\UserDataSource;
use App\Domain\User;
use App\Infrastructure\Controllers\GetUserListController;
use GetUsersController;
use Mockery;
use Tests\TestCase;
use App\Infrastructure\Persistence\FileUserDataSource;

class GetUserListControllerTest extends TestCase
{
    function setUp(): void
    {
        parent::setUp();

        $this->fileUserDataSource = Mockery::mock(FileUserDataSource::class);
        $this->getUserListController = new GetUserListController($this->fileUserDataSource);
        $this->app->bind(GetUserListController::class, function(){
            return $this->getUserListController;
        });
    }
    /**
     * @test
     */
    public function callReturnsUserList()
    {
        $users = [new User(1, "user1@email.com"), new User(2, "user2@email.com")];

        $this->fileUserDataSource
            ->expects('getAll')
            ->andReturn($users);

        $response = $this->get('/api/users');
        $user_ids = array_map(function ($user){
            return $user->getId();
        }, $users);

        $response->assertExactJson(['status' => 'Ok', 'user_list' => $user_ids]);
    }
}
