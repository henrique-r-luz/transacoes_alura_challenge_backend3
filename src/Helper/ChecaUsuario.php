<?php

namespace App\Helper;

use App\Entity\User;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAccountStatusException;

class ChecaUsuario implements UserCheckerInterface
{
    public function checkPreAuth(UserInterface $user): void
    {
        /** @var User */
        $usuario = $user;
        if ($usuario->getAtivo() === false) {
            throw new CustomUserMessageAccountStatusException('Usuário está desativado. ');
        }

        // the message passed to this exception is meant to be displayed to the user
        // throw new CustomUserMessageAccountStatusException('Your user account no longer exists.');
    }

    public function checkPostAuth(UserInterface $user): void
    {
    }
}
