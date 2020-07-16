<?php


namespace App\Validator;


use App\Entity\UsersEntity;
use Doctrine\ORM\EntityManagerInterface;
use phpDocumentor\Reflection\Types\Nullable;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\Constraints\Required;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\HttpKernel\Exception\LengthRequiredHttpException;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

class UserValidate implements UserValidateInterface
{
    private $validator;
    private $entityManager;

    public function __construct(ValidatorInterface $validator, EntityManagerInterface $entityManagerInterface)
    {
        $this->validator = $validator;
        $this->entityManager = $entityManagerInterface;
    }

    public function userValidator(Request $request, $type)
    {
        $input = json_decode($request->getContent(), true);

        $constraints = new Assert\Collection([
            'userName' => [
                new Required(),
                new Assert\NotBlank(),
              
                
            ],
            'email' => [
                new Required(),
                new Assert\NotBlank(),
                 
                // new UniqueEntity([
                //     'fields' => ['host', 'port'],
                //     'errorPath' => 'port',
                //     'message' => 'This port is already in use on that host.',
                // ])
                
            ],
            'password' => [
                new Required(),
                new Assert\NotBlank(),
                new Assert\Length([
                    'min' => 8,
                    'max' => 16,
                    'minMessage' => 'Your first name must be at least {{ limit }} characters long',
                    'maxMessage' => 'Your first name cannot be longer than {{ limit }} characters',
                    'allowEmptyString' => false,
                    
                ])
            ]
            
        ]);

        if ($type == 'create') {
            unset($constraints->fields['id']);
        }
       
        $violations = $this->validator->validate($input, $constraints);

        if (count($violations) > 0) {
            $accessor = PropertyAccess::createPropertyAccessor();

            $errorMessages = [];

            foreach ($violations as $violation) {
                $accessor->setValue($errorMessages,
                    $violation->getPropertyPath(),
                    $violation->getMessage());
            }

            $result = json_encode($errorMessages);

            return $result;
        }


        return null;
    }
}