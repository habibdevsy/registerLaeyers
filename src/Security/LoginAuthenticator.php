<?php

namespace App\Security;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use App\Entity\UsersEntity;


class LoginAuthenticator extends AbstractGuardAuthenticator
{
    private $passwordEncoder;
    public function __construct(UserPasswordEncoderInterface $passwordEncoder, RouterInterface $router,EntityManagerInterface $em)
    {
        $this->passwordEncoder = $passwordEncoder;
        $this->em = $em;
       
    }
    public function supports(Request $request)
   {
       return $request->get("_route") === "login" && $request->isMethod("POST");
   }
   public function getCredentials(Request $request)
   {
    return json_decode($request->getContent(), true);
    //    return [
    //        'email' => $request->request->get("email"),
    //        'password' => $request->request->get("password")
    //    ];
   }
   public function getUser($credentials, UserProviderInterface $userProvider)
   {
       // return  $userProvider->loadUserByUsername($credentials['email']);
       //$UsersEntity=$this->UsersEntity;
       $email = $userProvider->loadUserByUsername($credentials['email']);

       $user = $this->em->getRepository('App:UsersEntity')
           ->findOneBy(['email' => $email]);
       if (!$user)
       {
           $user = new UsersEntity($email);
           $user->getEmail();
           $user->getUserName();
           $user->setRoles(["ROLE_USER"]);
           $user->setPassword($this->passwordEncoder->encodePassword(
               $user,
                $user->getPassword()));
          
           $this->em->persist($user);
           $this->em->flush();
       }

       return $user;
  

   }
   public function checkCredentials($credentials, UserInterface $user)
   {
       return $this->passwordEncoder->isPasswordValid($user, $credentials['password']);
   }
   public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
   {
       return new JsonResponse([
           'error' => $exception->getMessageKey()
       ], 400);
   }
   public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
   {
       return new JsonResponse([
           'result' => true
       ]);
   }
   public function start(Request $request, AuthenticationException $authException = null)
   {
       return new JsonResponse([
           'error' => 'Access Denied'
       ]);
   }
   public function supportsRememberMe()
   {
       return false;
   }
}
