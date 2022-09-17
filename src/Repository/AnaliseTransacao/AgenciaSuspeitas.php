<?php

namespace App\Repository\AnaliseTransacao;


class AgenciaSuspeitas
{


    public function sql()
    {
        return  "(" . $this->contaEntrada() . ") union (" . $this->contaSaida() . ")";
    }


    private function contaEntrada()
    {
        return $this->select('Entrada') .
            $this->from() .
            $this->joinEntrada() .
            $this->where() .
            $this->groupBy() .
            $this->having();
    }

    private function joinEntrada()
    {
        return "inner join conta_bancaria on(conta_bancaria.id = transacao.conta_bancaria_destino_id) ";
    }


    private function contaSaida()
    {
        return $this->select('Saída') .
            $this->from() .
            $this->joinSaida() .
            $this->where() .
            $this->groupBy() .
            $this->having();
    }


    private function joinSaida()
    {
        return "inner join conta_bancaria on(conta_bancaria.id = transacao.conta_bancaria_origem_id)";
    }


    private function select($tipo)
    {
        return "select conta_bancaria.nome_banco, " .
            "conta_bancaria.agencia, " .
            "sum(transacao.valor) total, " .
            "'" . $tipo . "' as tipo_operacao ";
    }


    private function from()
    {
        return "from transacao ";
    }


    private function where()
    {
        return "where EXTRACT(YEAR FROM transacao.data) = :ano " .
            "and EXTRACT(MONTH FROM transacao.data) = :mes ";
    }


    private function groupBy()
    {
        return "group by conta_bancaria.agencia, " .
            "conta_bancaria.nome_banco, " .
            "tipo_operacao ";
    }

    private function having()
    {
        return "having sum(transacao.valor) >= :limite ";
    }
}
