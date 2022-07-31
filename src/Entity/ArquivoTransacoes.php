<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

class ArquivoTransacoes
{
    /** 
     * @ORM\Column(type="string")
     */
    private $arquivo;


    public function getArquivo()
    {
        return $this->arquivo;
    }


    public function setArquivo($arquivo)
    {
        $this->arquivo = $arquivo;
    }
}
