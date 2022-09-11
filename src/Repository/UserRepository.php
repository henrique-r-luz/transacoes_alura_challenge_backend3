<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\ORM\EntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function getUsers()
    {
        $sql = $this->createQueryBuilder('u')
            ->orderBy('u.nome', 'ASC')
            ->andWhere('u.email <> :email')
            ->andWhere('u.ativo = true')
            ->setParameter('email', User::emailAdmin);
        $query = $sql->getQuery();

        return $query->execute();
    }
}
