<?php

namespace App\Validacao\Import;

use Symfony\Component\Validator\Context\ExecutionContextInterface;

class ValidaUser
{
    public static function validate($object, ExecutionContextInterface $context, $payload)
    {
        /* print_r($payload);
        exit();
        $context->buildViolation('This name sounds totally fake!')
            ->atPath('nome')
            ->addViolation();*/
    }
}
