<?php

namespace App\Repository;

use App\Entity\Import;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

class ImportRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Import::class);
    }


    public function allImport()
    {
        $sql = $this->createQueryBuilder('import')
            ->innerJoin('import.transacoes', 'transacao');
        //->orderBy('import', 'DESC');
        $query = $sql->getQuery();

        return $query->execute();
    }
}
