<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\TransacaoRepository;
use Doctrine\ORM\Mapping\UniqueConstraint;

#[ORM\Entity(repositoryClass: TransacaoRepository::class)]
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

    #[ORM\ManyToOne(targetEntity:"Import")]
    #[ORM\JoinColumn(name:'import_id')]
    private  $import;
    
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


    public function setImport(Import $import){
        $this->import = $import;
    }

    public function getImport(){
        return $this->import;
    }

    /**
     * Get the value of data
     */ 
    public function getData()
    {
        return $this->data->format('d/m/Y H:i:s');
    }

    public function getDataGrid()
    {
        return $this->data->format('d/m/Y');
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
}
