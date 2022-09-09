<?php

namespace App\Controller;

use App\Entity\Roles;
use App\Entity\User;
use App\Repository\Operacoes\Operacao;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class LoginController extends AbstractController
{
    #[Route('/login', name: 'app_login')]
    public function index(
        AuthenticationUtils $authenticationUtils,
        UserPasswordHasherInterface $passwordHasher,
        ManagerRegistry $doctrine
    ): Response {
        $email = User::emailAdmin;
        $adminUser = $doctrine->getRepository(User::class)->findBy(['email' => $email]);
        if (empty($adminUser)) {
            $user = new User();
            $senha = '123999';
            $user->setNome("Admin");
            $user->setEmail($email);
            $hashedPassword = $passwordHasher->hashPassword(
                $user,
                $senha,
            );
            $user->setSenha($hashedPassword);
            $user->setRoles([Roles::ROLE_ADM]);
            $operacao = new Operacao($doctrine);
            $operacao->save($user);
        }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $userName = $authenticationUtils->getLastUsername();

        return $this->render('login/index.html.twig', [
            'controller_name' => 'LoginController',
            'last_username' => $userName,
            'error'         => $error,
        ]);
    }

    #[Route('/logout', name: 'app_logout', methods: ['GET'])]
    public function logout()
    {
        // controller can be blank: it will never be called!
        return $this->redirectToRoute('import');
    }
}
