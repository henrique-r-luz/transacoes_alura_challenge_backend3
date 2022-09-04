<?php

namespace App\Validacao\Import;

use Doctrine\ORM\EntityManager;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use App\Entity\User;

class ValidaUser
{

    public static function validate($object, ExecutionContextInterface $context, $payload)
    {
    }


    public static function uniqueEmail($object, ExecutionContextInterface $context, $payload)
    {
        $kernel = $GLOBALS['app'];
        $container = $kernel->getContainer();
        $doctrine = $container->get('doctrine');
        $user = $doctrine->getRepository(User::class)->findBy(['email' => $object]);
        if (!empty($user)) {
            $context->buildViolation('O email já foi cadastrado! ')
                ->atPath('email')
                ->addViolation();
        }
    }
}
