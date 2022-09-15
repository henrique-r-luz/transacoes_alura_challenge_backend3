<?php

namespace App\Controller;

use Throwable;
use App\Services\ImportLista;
use App\Helper\ArulaException;
use App\Services\ImportServices;
use App\Services\TransacaoLista;
use App\Entity\ArquivoTransacoes;
use App\Form\ArquivoTransacoesType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;



class ImportController extends AbstractController
{
    #[Route(path: '/', name: 'home')]
    public function home()
    {

        return $this->redirectToRoute('import');
    }

    #[Route(path: '/app/import', name: 'import', methods: ["POST", "GET"])]
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
                    $importServices->importa($form->get('arquivo'));
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
            $this->addFlash('danger', 'Ocorreu um errro não esperado' . $e->getMessage());
        } finally {
            return $this->renderForm('import/import.html.twig', [
                'form' => $form,
                'services' => $importLista->dataProvider($request)
            ]);
        }
    }

    #[Route(path: '/app/import/detalhes/{id}', name: 'import_detalhes')]
    public function detalhesImport(
        int $id,
        Request $request,
        TransacaoLista $transacaoLista
    ) {
        $transacoes = $transacaoLista->dataProvider($request, $id);
        foreach ($transacoes as $transacao) {
            $dataTransacao = $transacao->getDataGrid();
            $dataImportacao = $transacao->getImport()->getData();
            $usuario = $transacao->getImport()->getUsuario()->getNome();
            break;
        }
        return $this->renderForm('import/detalhar.html.twig', [
            'transacao' => $transacoes,
            'dataTransacao' => $dataTransacao,
            'dataImportacao' => $dataImportacao,
            'usuario' => $usuario
        ]);
    }
}
