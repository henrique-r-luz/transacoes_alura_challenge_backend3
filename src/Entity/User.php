<?php

namespace App\Entity;


use Doctrine\ORM\Mapping as ORM;
use App\Validacao\Import\ValidaUser;
use Doctrine\ORM\Mapping\UniqueConstraint;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAccountStatusException;
use App\Entity\Traits\UserGetSet;

#[ORM\Entity(repositoryClass: "App\Repository\UserRepository")]
#[ORM\Table(name: 'users')]
#[UniqueConstraint(name: "unique_user_email", columns: ["email"])]


#[Assert\Callback([ValidaUser::class, 'uniqueEmail'])]
#[Assert\Callback([ValidaUser::class, 'validaUserAtivo'])]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{

    use UserGetSet;
    const emailAdmin = "admin@email.com.br";

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private $id = null;

    #[ORM\Column(type: "string")]
    #[Assert\NotBlank]
    private String $nome;

    #[ORM\Column(type: "string")]
    #[Assert\NotBlank]
    #[Assert\Email(
        message: 'O email {{ value }} não é valido.',
    )]
    #[Assert\Callback([ValidaUser::class, 'validaEmailAdmin'])]
    private String $email;

    #[ORM\Column(type: 'json')]
    private $roles = [];

    #[ORM\Column(type: "text")]
    private string $senha;

    #[ORM\OneToMany(targetEntity: "Import", mappedBy: "usuario")]
    private $import;

    #[ORM\Column(type: "boolean", nullable: false, options: ["default" => 1])]
    private bool $ativo = true;


    public function __construct()
    {
        $this->import =  new ArrayCollection();
    }

    public function addImport(Import $import)
    {
        $this->import->add($import);
        $import->setUsuario($this);
    }

    public function getRoles(): array
    {
        return $this->roles;
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * Removes sensitive data from the user.
     *
     * This is important if, at any given point, sensitive information like
     * the plain-text password is stored on this object.
     */
    public function eraseCredentials()
    {
    }

    /**
     * Returns the identifier for this user (e.g. its username or email address).
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * Returns the hashed password used to authenticate the user.
     *
     * Usually on authentication, a plain-text password will be compared to this value.
     */
    public function getPassword(): string
    {
        return $this->senha;
    }

    public function getusername()
    {
        return $this->email;
    }
}
