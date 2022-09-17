<?php

namespace App\Repository;

use App\Entity\Transacao;
use Doctrine\Persistence\ManagerRegistry;
use App\Repository\AnaliseTransacao\ContasSuspeitas;
use App\Repository\AnaliseTransacao\AgenciaSuspeitas;
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

        return $query->execute();
    }


    public function analiseContaBancaria($mes, $ano)
    {
        $valorLimiteTransacao = 1000000;
        $conn = $this->getEntityManager()->getConnection();
        $contasSuspeitas = new ContasSuspeitas();
        $stmt = $conn->prepare($contasSuspeitas->sql());
        $resultSet = $stmt->executeQuery(
            [
                'limite' => $valorLimiteTransacao,
                'ano' => $ano,
                'mes' => $mes
            ]
        );
        return $resultSet->fetchAllAssociative();
    }


    public function analiseAgencia($mes, $ano)
    {
        $valorLimiteTransacao = 1000000000;
        $conn = $this->getEntityManager()->getConnection();
        $agenciaSuspeitas = new AgenciaSuspeitas();
        $stmt = $conn->prepare($agenciaSuspeitas->sql());
        $resultSet = $stmt->executeQuery(
            [
                'limite' => $valorLimiteTransacao,
                'ano' => $ano,
                'mes' => $mes
            ]
        );
        return $resultSet->fetchAllAssociative();
    }
}
