<?php

namespace App\Validacao\Import;

use App\Entity\User;
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


    public function validaEmailAdmin($object, ExecutionContextInterface $context, $payload)
    {
        if ($object === User::emailAdmin) {
            $context->buildViolation('Esse email está reservado para o administrador !')
                ->atPath('email')
                ->addViolation();
        }
    }
}
