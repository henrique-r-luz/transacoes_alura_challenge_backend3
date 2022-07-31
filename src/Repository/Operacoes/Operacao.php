<?php

namespace App\Repository\Operacoes;
use Doctrine\Persistence\ManagerRegistry;

class Operacao
{

    private $doctrine;
    function __construct(ManagerRegistry $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    public function save($entidade)
    {
        $entityManager = $this->doctrine->getManager();
        $entityManager->persist($entidade);
        $entityManager->flush();
    }

    public function update()
    {
    }


    public function delete()
    {
    }
}
