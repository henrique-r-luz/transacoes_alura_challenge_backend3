<?php

namespace App\Entity;


use JsonSerializable;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: "App\Repository\ContaBancariaRepository")]
#[ORM\Table(name: "conta_bancaria")]
class ContaBancaria implements JsonSerializable
{

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private int $id;

    #[ORM\Column(type: "string")]
    #[Assert\NotBlank]
    private String $nome_banco;

    #[Assert\NotBlank]
    #[ORM\Column(type: "string")]
    private String $agencia;

    #[Assert\NotBlank]
    #[ORM\Column(type: "string")]
    private String $conta;



    /**
     * Get the value of nome_banco
     */
    public function getNome_banco()
    {
        return $this->nome_banco;
    }

    /**
     * Set the value of nome_banco
     *
     * @return  self
     */
    public function setNome_banco($nome_banco)
    {
        $this->nome_banco = $nome_banco;

        return $this;
    }

    /**
     * Get the value of agencia
     */
    public function getAgencia()
    {
        return $this->agencia;
    }

    /**
     * Set the value of agencia
     *
     * @return  self
     */
    public function setAgencia($agencia)
    {
        $this->agencia = $agencia;

        return $this;
    }

    /**
     * Get the value of conta
     */
    public function getConta()
    {
        return $this->conta;
    }

    /**
     * Set the value of conta
     *
     * @return  self
     */
    public function setConta($conta)
    {
        $this->conta = $conta;

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

    public function jsonSerialize()
    {
        return [
            'id' => $this->getId(),
            'conta' => $this->getConta(),
            'agencia' => $this->getAgencia(),
            'nome_banco' => $this->getNome_banco()
        ];
    }
}
