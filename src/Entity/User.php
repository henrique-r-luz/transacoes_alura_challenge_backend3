<?php

namespace App\Entity;


use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Mime\Message;
use App\Validacao\Import\ValidaUser;
use Doctrine\ORM\Mapping\UniqueConstraint;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

#[ORM\Entity(repositoryClass: "App\Repository\UserRepository")]
#[ORM\Table(name: 'users')]
#[UniqueConstraint(name: "unique_user_email", columns: ["email"])]
#[Assert\Callback([ValidaUser::class, 'uniqueEmail'])]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
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


    public function __construct()
    {
        $this->import =  new ArrayCollection();
    }

    public function addImport(Import $import)
    {
        $this->import->add($import);
        $import->setUsuario($this);
    }


    public function getImport()
    {
        return $this->import;
    }

    /**
     * Get the value of nome
     */
    public function getNome()
    {
        return $this->nome;
    }

    /**
     * Set the value of nome
     *
     * @return  self
     */
    public function setNome($nome)
    {
        $this->nome = $nome;

        return $this;
    }

    /**
     * Get the value of email
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set the value of email
     *
     * @return  self
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get the value of id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set the value of id
     *
     * @return  self
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get the value of senha
     */
    public function getSenha()
    {
        return $this->senha;
    }

    /**
     * Set the value of senha
     *
     * @return  self
     */
    public function setSenha($senha)
    {
        $this->senha = $senha;

        return $this;
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
