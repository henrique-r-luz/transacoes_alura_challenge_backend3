<?php

namespace App\Controller;

use App\Services\ImportLista;
use App\Helper\ArulaException;
use App\Services\ImportServices;
use App\Entity\ArquivoTransacoes;
use App\Form\ArquivoTransacoesType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Throwable;

class ImportController extends AbstractController
{

    #[Route(path: '/', methods: ["POST", "GET"])]
    public function upload(
        Request $request,
        ImportServices $importServices,
        ImportLista $importLista,
    ) {

        try {
            $arquivoTransacoes = new ArquivoTransacoes();
            $form = $this->createForm(ArquivoTransacoesType::class, $arquivoTransacoes);
            $form->handleRequest($request);
            if ($form->isSubmitted()) {
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
            }
        } catch (ArulaException $e) {
            $this->addFlash('danger', $e->getMessage());
        } catch (Throwable $e) {
            $this->addFlash('danger', 'Ocorreu um errro não esperado');
        }
        return $this->renderForm('import/import.html.twig', [
            'form' => $form,
            'services' => $importLista->dataProvider($request)
        ]);
    }
}
