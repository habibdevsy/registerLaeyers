<?php


namespace App\Validator;

use Symfony\Component\HttpFoundation\Request;

interface UserValidateInterface
{
    public function userValidator(Request $request, $type);

}