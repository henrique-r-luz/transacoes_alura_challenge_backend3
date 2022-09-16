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
        // $array = $query->getArrayResult();
        //  return $array;
        // print_r($array);
        //exit();

        return $query->execute();
    }


    public function analiseContaBancaria($mes, $ano)
    {
        $valorLimiteTransacao = 1000000;
        $contaEntrada = $this->createQueryBuilder('transacao')
            ->select(
                'contaDestino.nome_banco',
                'contaDestino.agencia',
                'contaDestino.conta',
                'SUM(transacao.valor) total',
                "'Entrada' tipo_operacao"
            )
            ->innerJoin('transacao.contaBancariaDestino', 'contaDestino')
            //->orderBy('transacao.valor', 'DESC')

            ->andWhere('YEAR(transacao.data) = :ano')
            ->andWhere('MONTH(transacao.data) = :mes')
            ->groupBy(
                'contaDestino.nome_banco',
                'contaDestino.agencia',
                'contaDestino.conta'
            )
            ->having('SUM(transacao.valor) >= :valorLimite')
            ->setParameter('valorLimite', $valorLimiteTransacao)
            ->setParameter('ano', $ano)
            ->setParameter('mes', $mes);

        $query = $contaEntrada->getQuery();

        return $query->execute();
    }
}
