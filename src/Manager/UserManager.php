<?php


namespace App\Manager;

use App\AutoMapping;
use App\Entity\UsersEntity;
use App\Repository\UsersRepository;
use App\Request\CreateUserRequest;

use App\Request\GetByIdRequest;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

class UserManager
{
    private $entityManager;
    private $userRepository;
    private $autoMapping;

    public function __construct(EntityManagerInterface $entityManagerInterface,usersRepository $userRepository, AutoMapping $autoMapping)
    {
        $this->entityManager = $entityManagerInterface;
        $this->userRepository=$userRepository;
        $this->autoMapping = $autoMapping;
    }

    public function create(CreateUserRequest $request)
    {
        $userEntity = $this->autoMapping->map(CreateUserRequest::class, UsersEntity::class, $request);
        $this->entityManager->persist($userEntity);
        $this->entityManager->flush();
        $this->entityManager->clear();
        return $userEntity;
    }

}