<?php

namespace App\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use App\Request\CreateUserRequest;
use App\Service\UserService;
use App\AutoMapping;
use AutoMapperPlus\Exception\UnregisteredMappingException;  
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Respons\CreateUsersResponse;
use App\Validator\UserValidateInterface;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;

class UsersController extends  BaseController
{
    private $userService;
    private $autoMapping;
    /**
     * 
     * UserController constructor.
     * @param UserService $userService
     */
    public function __construct(UserService $userService,AutoMapping $autoMapping)
    {
        $this->userService = $userService;
        $this->autoMapping=$autoMapping;
    }


    /**
     * 
     * @Route("/register",name="registerUser",methods={"POST"})
     * @param Request $request
     * @return Response
     */
    public function register(Request $request, UserValidateInterface $userValidator)
    {
        //Validation
        $validateResult = $userValidator->userValidator($request, 'create');

        if (!empty($validateResult)) {
            $resultResponse = new Response($validateResult, Response::HTTP_OK, ['content-type' => 'application/json']);
            $resultResponse->headers->set('Access-Control-Allow-Origin', '*');
            return $resultResponse;
            $errorsString = (string) $errors;

            return new Response($errorsString);
        }
        $data = json_decode( $request->getContent(),true);
        $request=$this->autoMapping->map(\stdClass::class,CreateUserRequest::class,(object)$data);
        $result = $this->userService->create($request);
        return $this->response($result, self::CREATE);
       //return $this->json(['data'=>$data]);
    }
    /**
     * @Route("/login", name="login", methods={"POST"})
     */
    public function login()
    {
        return $this->json(['result' => true]);
    }

    
    /**
     * @Route("/profile", name="api_profile")
     * @IsGranted("ROLE_USER")
     */
    public function profile()
    {
        return $this->json([
            'user' => $this->getUser()
         ], 
         200, 
         [], 
         [
            'groups' => ['api']
         ]
      );
    }
}
