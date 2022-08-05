<?php

namespace App\Validacao\Import;

use App\Entity\Transacao;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;


class ValidaImport
{
    private Transacao $transacao;
    private $doctrine;

    public function __construct(
        Transacao $transacao,
        ManagerRegistry $doctrine
    ) {
        $this->transacao = $transacao;
        $this->doctrine = $doctrine;
    }

    public function verificaTransacao()
    {
        $conn = $this->doctrine->getConnection();
        $sql = $conn->createQueryBuilder();
        $query =  $sql->select(['transacao.id', 'transacao.data'])
            ->from('Transacao', 'transacao')
            ->where('cast(transacao.data as DATE) = :data')
            ->setParameter('data', '2022-02-01')
            ->execute()
            ->fetchAllAssociative();
        
        //$result = $resp->getQuery();    
           // ->execute()
           // ->fetchAllAssociative();



        print_r($query);

        //  print_r($array);
        exit();
    }
}
