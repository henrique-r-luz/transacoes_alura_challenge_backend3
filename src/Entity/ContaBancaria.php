<?php

namespace App\Entity;


use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\UniqueConstraint;
use Doctrine\Common\Collections\ArrayCollection;

#[ORM\Entity(repositoryClass: "App\Repository\ContaBancariaRepository")]
#[ORM\Table(name: "conta_bancaria")]
#[UniqueConstraint(name: "unique_conta_bancaria", columns: ["nome_banco","agencia","conta"])]

class ContaBancaria
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

    #[ORM\OneToMany(targetEntity:"Transacao",  mappedBy:"contaBancariaOrigem")]
    private  $transacoesOrigem;

    #[ORM\OneToMany(targetEntity:"Transacao",  mappedBy:"contaBancariaDestino")]
    private  $transacoesDestino;

    function __construct()
    {
       $this->transacoesOrigem =  new ArrayCollection();
       $this->transacoesDestino = new ArrayCollection();
    }

    public function addTransacaoOrigem(Transacao $transacaoOrigem){
        $this->transacoes->add($transacaoOrigem);
        $transacaoOrigem->setContaBancariaOrigem($this);
    }

    public function getTransacoesOrigem(){
        return $this->transacoesOrigem;
    }

    public function addTransacaoDestino(Transacao $transacaoDestino){
        $this->transacoes->add($transacaoDestino);
        $transacaoDestino->setContaBancariaDestino($this);
    }

    public function getTransacoesDestino(){
        return $this->transacoesDestino;
    }

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
}
