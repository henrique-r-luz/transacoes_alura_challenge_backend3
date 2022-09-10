<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;


#[ORM\Entity(repositoryClass: "App\Repository\ImportRepository")]
#[ORM\Table(name: "import")]
class Import
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private int $id;

    #[ORM\Column(type: "datetime")]
    private  $data;

    #[ORM\OneToMany(targetEntity: "Transacao",  mappedBy: "import")]
    private $transacoes;

    #[ORM\ManyToOne(targetEntity: "User", inversedBy: 'import')]
    private  $usuario;


    public function __construct()
    {
        $this->transacoes =  new ArrayCollection();
    }

    public function addTransacao(Transacao $transacao)
    {
        $this->transacoes->add($transacao);
        $transacao->setImport($this);
    }


    /**
     * Get the value of data
     */
    public function getData()
    {
        return $this->data->format('d/m/Y H:i:s');
    }

    public function getDataFormat()
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
     * Get the value of id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get the value of transacoes
     */
    public function getTransacoes()
    {
        return $this->transacoes;
    }

    public function getDataPrimeiraTransacao()
    {
        if (isset($this->transacoes[0])) {
            return $this->transacoes[0]->getDataGrid() ?? '';
        }
        return '';
    }

    /**
     * Get the value of usuario
     */
    public function getUsuario()
    {
        return $this->usuario;
    }

    /**
     * Set the value of usuario
     *
     * @return  self
     */
    public function setUsuario($usuario)
    {
        $this->usuario = $usuario;

        return $this;
    }
}
