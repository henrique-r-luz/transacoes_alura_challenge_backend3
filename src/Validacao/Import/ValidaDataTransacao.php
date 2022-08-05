<?php

namespace App\Validacao\Import;

use App\Entity\transacao;

class ValidaDataTransacao
{

    private Transacao $transacao;
    private $dataInicial;

    public function __construct(Transacao $transacao, $dataInicial)
    {
        $this->transacao = $transacao;
        $this->dataInicial = $dataInicial;
        
    }
    public function valida()
    {
        if($this->transacao->getDataGrid()!==$this->dataInicial->format('d/m/Y')){
           return false;
        }
        return true;
    }
}
