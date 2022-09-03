<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Services\UserLista;
use App\Repository\Operacoes\Operacao;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserController extends AbstractController
{

    #[Route(path: '/user', name: 'user_index')]
    public function index(
        Request $request,
        UserLista $userLista,
    ) {
        return $this->renderForm('user/user.html.twig', [

            'services' => $userLista->dataProvider($request)
        ]);
    }

    #[Route(path: '/user/create', methods: ["POST", "GET"])]
    public function create(
        Request $request,
        ManagerRegistry $doctrine
    ) {
        $user  = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();
            $user->setSenha('11223');
            $operacao = new Operacao($doctrine);
            $operacao->save($user);

            $this->addFlash('success', 'Dados inseridos com sucesso!');
            return $this->redirectToRoute('user_index');
        }
        return $this->renderForm('user/form.html.twig', [
            'form' => $form
        ]);
    }

    #[Route(path: '/user/update/{id}')]
    public function update(int $id)
    {
        echo ' update ' . $id;
        exit();
    }

    #[Route(path: '/user/delete/{id}')]
    public function delete(int $id)
    {
    }
}
