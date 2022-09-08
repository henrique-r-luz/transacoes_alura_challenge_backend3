<?php

namespace App\Validacao\Import;

use Symfony\Component\Validator\Context\ExecutionContextInterface;


class ValidaUser
{

    public static function uniqueEmail($object, ExecutionContextInterface $context, $payload)
    {

        $user = $object;
        $kernel = $GLOBALS['app'];
        $container = $kernel->getContainer();
        $doctrine = $container->get('doctrine');
        $conn = $doctrine->getConnection();
        $sql = $conn->createQueryBuilder();
        $sql->from('users', 'usuario')
            ->select('id')
            ->andWhere('email = :email')
            ->setParameter('email', $user->getEmail());
        if ($user->getId() != null) {
            $sql->andWhere($sql->expr()->neq('usuario.id', ':id'))
                ->setParameter('id', $user->getId());
        }
        $query = $sql->execute()
            ->fetchAllAssociative();
        if (!empty($query)) {
            $context->buildViolation('O email já foi cadastrado! ')
                ->atPath('email')
                ->addViolation();
        }
    }
}
