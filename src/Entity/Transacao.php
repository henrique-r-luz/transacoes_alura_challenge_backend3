<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\UniqueConstraint;
use Laminas\Code\Generator\EnumGenerator\Name;

#[ORM\Entity(repositoryClass: "App\Repository\TransacaoRepository")]
#[ORM\Table(name: "transacao")]
#[UniqueConstraint(name: "unique_conta_origem_conta_destino_data", columns: ["conta_bancaria_origem_id","conta_bancaria_destino_id","data"])]

class Transacao
{

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type : "integer")]
    private int $id;
    
    #[ORM\ManyToOne(targetEntity:"ContaBancaria")]
    #[ORM\JoinColumn(name:'conta_bancaria_origem_id')]
    private  $contaBancariaOrigem;

    #[ORM\ManyToOne(targetEntity:"ContaBancaria")]
    #[ORM\JoinColumn(name:'conta_bancaria_destino_id')]
    private  $contaBancariaDestino;
    
    #[ORM\Column(type:"decimal",precision:8, scale:2)]
    private $valor;
    #[ORM\Column(type:"datetime")]
    private $data;
   


    public function setContaBancariaOrigem(ContaBancaria $contaBancariaOrigem){
        $this->contaBancariaOrigem = $contaBancariaOrigem;
    }

    public function setContaBancariaDestino(ContaBancaria $contaBancariaDestino){
        $this->contaBancariaDestino = $contaBancariaDestino;
    }

    /**
     * Get the value of data
     */ 
    public function getData()
    {
        return $this->data;
    }

    /**
     * Set the value of data
     *
     * @return  self
     */ 
    public function setData($data)
    {
        $this->data = $data;

        return $this;
    }

    /**
     * Get the value of valor
     */ 
    public function getValor()
    {
        return $this->valor;
    }

    /**
     * Set the value of valor
     *
     * @return  self
     */ 
    public function setValor($valor)
    {
        $this->valor = $valor;

        return $this;
    }
}
