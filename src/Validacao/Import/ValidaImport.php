<?php

namespace App\Validacao\Import;

use DateTime;
use App\Entity\Transacao;
use Doctrine\Persistence\ManagerRegistry;


class ValidaImport
{
    private DateTime $data;
    private $doctrine;

    public function __construct(
        DateTime $data,
        ManagerRegistry $doctrine
    ) {
        $this->data = $data;
        $this->doctrine = $doctrine;
    }

    public function verificaTransacao()
    {
        $conn = $this->doctrine->getConnection();
        $sql = $conn->createQueryBuilder();
        $query =  $sql->select(['transacao.id', 'transacao.data'])
            ->from('Transacao', 'transacao')
            ->where('cast(transacao.data as DATE) = :data')
            ->setParameter('data', $this->data->format('Y-m-d'))
            ->setMaxResults(1)
            ->execute()
            ->fetchAllAssociative();

        if (!empty($query)) {
            return false;
        }
        return true;
    }

    public function getMessage(){
        return 'A transação já foi importada.';
    }
}
