<?php

namespace App\Controller;

use Throwable;
use App\Entity\User;
use App\Form\UserType;
use App\Services\UserLista;
use App\Helper\ArulaException;
use App\Services\UserServices;
use App\Repository\Operacoes\Operacao;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;

class UserController extends AbstractController
{

    #[Route(path: 'app/user', name: 'user_index')]

    public function index(
        Request $request,
        UserLista $userLista,
    ) {
        return $this->renderForm('user/user.html.twig', [

            'services' => $userLista->dataProvider($request)
        ]);
    }

    #[Route(path: 'app/user/create', name: 'user_create', methods: ["POST", "GET"])]
    public function create(
        Request $request,
        UserServices $userServices,
    ) {
        try {
            $titulo = 'Cadastra Usuário';
            $pagina = 'user/form.html.twig';
            $user  = new User();
            $form = $this->createForm(UserType::class, $user);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $userServices->setUser($user);
                $userServices->setForm($form);
                $userServices->salvar();
                $this->addFlash('success', 'Dados inseridos com sucesso!');
                return $this->redirectToRoute('user_index');
            }
        } catch (ArulaException | TransportExceptionInterface $e) {
            $this->addFlash('danger', 'Erro: ' . $e->getMessage());
            return $this->renderForm($pagina, [
                'form' => $form,
                'titulo' => $titulo
            ]);
        } catch (Throwable $e) {
            $this->addFlash('danger', 'Um erro inesperado Ocorreu. ' . $e->getMessage());
            return $this->renderForm($pagina, [
                'form' => $form,
                'titulo' => $titulo
            ]);
        }
        return $this->renderForm($pagina, [
            'form' => $form,
            'titulo' => $titulo
        ]);
    }

    #[Route(path: 'app/user/update/{id}', name: 'user_update')]
    public function update(
        int $id,
        ManagerRegistry $doctrine,
        Request $request,
        UserServices $userServices,
    ) {
        try {
            $titulo = 'Atualiza Usuário';
            $pagina = 'user/form.html.twig';
            $user  = $doctrine->getRepository(User::class)->find($id);
            $form = $this->createForm(UserType::class, $user);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $userServices->setUser($user);
                $userServices->setForm($form);
                $userServices->update();
                $this->addFlash('success', 'Dados Atualizados com sucesso!');
                return $this->redirectToRoute('user_index');
            }
        } catch (Throwable $e) {
            $this->addFlash('danger', 'Um erro inesperado Ocorreu. ');
            return $this->renderForm($pagina, [
                'form' => $form,
                'titulo' => $titulo
            ]);
        } catch (ArulaException $e) {
            $this->addFlash('danger', 'Erro: ' . $e->getMessage());
            return $this->renderForm($pagina, [
                'form' => $form,
                'titulo' => $titulo
            ]);
        }
        return $this->renderForm('user/form.html.twig', [
            'form' => $form,
            'titulo' => $titulo
        ]);
    }


    #[Route(path: 'app/user/delete/{id}', name: 'user_delete')]
    public function delete(
        int $id,
        UserServices $userServices,
    ) {
        try {
            $userServices->delete($id);
            $this->addFlash('success', 'O usuário foi desabilitado. ');
        } catch (ArulaException $e) {
            $this->addFlash('danger', $e->getMessage());
        } catch (Throwable $e) {
            $this->addFlash('danger', 'Um erro inesperado ocorreu');
        } finally {
            return $this->redirectToRoute('user_index');
        }
    }
}
