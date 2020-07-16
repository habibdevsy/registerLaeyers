<?php


namespace App\Respons;


class CreateUsersResponse
{
    public $email;
    public $password;
    public $userName;
    /**
     * @return mixed
     */
    public function getUserName(): ?string
    {
        return $this->userName;
    }
    /**
     * @param mixed $userName
     */
    public function setUserName(string $userName): self
    {
        $this->userName = $userName;

        return $this;
    }


    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($Email): void
    {
        $this->Email = $Email;
    }

      /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param mixed $fullName
     */
    public function setPassword($password): void
    {
        $this->password = $password;
    }
}