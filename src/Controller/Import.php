<?php

namespace App\Controller;


use Exception;
use App\Services\ImportLista;
use App\Services\ImportServices;
use App\Entity\ArquivoTransacoes;
use App\Form\ArquivoTransacoesType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class Import extends AbstractController
{

    #[Route('/')]
    public function import(
        ImportLista $importLista,
        Request $request,
    ) {

        $arquivoTransacoes = new ArquivoTransacoes();
        $form = $this->createForm(ArquivoTransacoesType::class, $arquivoTransacoes);
        // $importLista->listaTodos();
        //get importacões
        return $this->render('import/import.html.twig', [
            'form' => $form->createView(),
            'services' => $importLista->dataProvider($request)
           
        ]);
    }

    #[Route(path: '/upload', methods: "POST")]
    public function upload(
        Request $request,
        ImportServices $importServices
    ) {
        try {
            $arquivoTransacoes = new ArquivoTransacoes();
            $form = $this->createForm(ArquivoTransacoesType::class, $arquivoTransacoes);
            $form->handleRequest($request);
            if ($form->isValid()) {
                $importServices->importa($form->get('arquivo')->getData());
                $importServices->salva();
                $this->addFlash('success', 'Dados inseridos com sucesso!');
            } else {
                $erros = '';
                foreach ($form->getErrors(true) as $error) {
                    $erros .= $error->getMessage() . "\n";
                }
                $this->addFlash('danger', $erros);
            }
        } catch (Exception $e) {
            $this->addFlash('danger', $e);
        }
        return $this->renderForm('import/import.html.twig', [
            'form' => $form,
        ]);
    }
}
