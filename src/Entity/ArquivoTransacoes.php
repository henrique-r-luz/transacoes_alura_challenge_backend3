<?php

namespace App\Entity;


class ArquivoTransacoes
{
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
