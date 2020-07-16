<?php


namespace App\Request;


class CreateUserRequest
{
    public $email;
    public $password;
    public $userName;


     /**
     * @ORM\Column(type="json")
     * @Groups("api")
     */
    private $roles = [];
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
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }
}