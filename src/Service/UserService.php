<?php


namespace App\Service;


use App\AutoMapping;
use App\Entity\UsersEntity;
use App\Manager\UserManager;
use App\Request\GetUserByIdResponse;

use App\Response\GetUsersResponse;
use Symfony\Component\HttpFoundation\Request;
use App\Respons\CreateUsersResponse;
class UserService

{
    private $userManager;
    private $autoMapping;


    public function __construct(UserManager $userManager, AutoMapping $autoMapping)
    {
        $this->userManager =$userManager;
        $this->autoMapping = $autoMapping;
    }

    public function create($request)
    {
        $userResult = $this->userManager->create($request);
        $response = $this->autoMapping->map(UsersEntity::class, CreateUsersResponse::class,
            $userResult);
        return $response;
    }
}