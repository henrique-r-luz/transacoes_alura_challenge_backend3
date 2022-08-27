<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Services\UserLista;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserController extends AbstractController
{

    #[Route(path: '/user')]
    public function index(
        Request $request,
        UserLista $userLista,
    ) {
        return $this->renderForm('user/user.html.twig', [

            'services' => $userLista->dataProvider($request)
        ]);
    }

    #[Route(path: '/user/create', methods: ["POST", "GET"])]
    public function create(Request $request)
    {
        $user  = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            echo 'olaaa';
            exit();
        }
        return $this->renderForm('user/create.html.twig', [
            'form' => $form
        ]);
    }

    #[Route(path: '/user/update/{id}')]
    public function update(int $id)
    {
        echo ' update ' . $id;
        exit();
    }

    #[Route(path: '/user/delete')]
    public function delete()
    {
    }
}
