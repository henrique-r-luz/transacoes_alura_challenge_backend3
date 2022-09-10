<?php

namespace App\Services;

use App\Entity\Transacao;
use App\Repository\UserRepository;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;

class TransacaoLista
{
    private $paginator;
    private $doctrine;

    public function __construct(
        PaginatorInterface $paginator,
        ManagerRegistry $doctrine
    ) {
        $this->paginator = $paginator;
        $this->doctrine = $doctrine;
    }

    private function paginate($query, $request)
    {
        return $this->paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            count($query)
        );
    }


    public function dataProvider($request)
    {
        /**@var UserRepository */
        $transacao = $this->doctrine->getRepository(Transacao::class)->findAll();
        return $this->paginate(
            $transacao,
            $request
        );
    }
}
