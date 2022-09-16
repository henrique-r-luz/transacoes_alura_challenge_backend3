<?php

namespace App\Services;

use App\Entity\Transacao;
use App\Repository\TransacaoRepository;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;

class AnaliseTransacoesContaLista
{
    private $paginator;
    private $doctrine;
    private $mes;
    private $ano;

    public function __construct(
        PaginatorInterface $paginator,
        ManagerRegistry $doctrine
    ) {
        $this->paginator = $paginator;
        $this->doctrine = $doctrine;
    }

    private function paginate($query, $request)
    {
        $limit =  count($query) == 0 ? 10 : count($query);
        return $this->paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            $limit
        );
    }


    public function dataProvider($request)
    {
        /** @var TransacaoRepository  */
        $transacoes = $this->doctrine->getRepository(Transacao::class);
        return $this->paginate(
            $transacoes->analiseContaBancaria($this->mes, $this->ano),
            $request
        );
    }

    /**
     * Set the value of mes
     *
     * @return  self
     */
    public function setMes($mes)
    {
        $this->mes = $mes;

        return $this;
    }

    /**
     * Set the value of ano
     *
     * @return  self
     */
    public function setAno($ano)
    {
        $this->ano = $ano;

        return $this;
    }
}
