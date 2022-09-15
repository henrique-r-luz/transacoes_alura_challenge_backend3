<?php

namespace App\Controller;

use App\Entity\Analise;
use App\Form\AnaliseForm;
use App\Services\AnaliseTransacoesLista;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AnaliseController extends AbstractController
{
    #[Route(path: '/app/analise/index', name: 'analise_index')]
    public function index(
        Request $request,
        AnaliseTransacoesLista $analiseTransacoesLista
    ) {
        $analiseTransacoes = null;
        $analiseConta = null;
        $analiseAgencia = null;
        $analise = new Analise();
        $form =  $this->createForm(AnaliseForm::class, $analise);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $analise = $form->getData();
            $analiseTransacoesLista->setAno($analise->ano);
            $analiseTransacoesLista->setMes($analise->mes);
            $analiseTransacoes = $analiseTransacoesLista->dataProvider($request);
        }

        return $this->renderForm('analise/index.html.twig', [
            'form' => $form,
            'analiseTransacoes' => $analiseTransacoes
            //'services' => $importLista->dataProvider($request)
        ]);
    }
}
