<?php

namespace App\Repository;


use App\Entity\Transacao;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

class TransacaoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Transacao::class);
    }

    public function analiseTransacao($mes, $ano)
    {
        $valorLimiteTransacao = 100000;
        $sql = $this->createQueryBuilder('transacao')
            ->orderBy('transacao.valor', 'DESC')
            ->andWhere('transacao.valor >= :valorLimite')
            ->andWhere('YEAR(transacao.data) = :ano')
            ->andWhere('MONTH(transacao.data) = :mes')
            ->setParameter('valorLimite', $valorLimiteTransacao)
            ->setParameter('ano', $ano)
            ->setParameter('mes', $mes);

        $query = $sql->getQuery();

        /* echo  $query->getSQL();
        echo $query->getParameters();
        exit();*/

        return $query->execute();
    }
}
