<?php

namespace App\Controller;

use App\Entity\Analise;
use App\Form\AnaliseForm;
use App\Services\AnaliseAgenciaLista;
use App\Services\AnaliseTransacoesLista;
use App\Services\AnaliseTransacoesContaLista;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AnaliseController extends AbstractController
{
    #[Route(path: '/app/analise/index', name: 'analise_index')]
    public function index(
        Request $request,
        AnaliseTransacoesLista $analiseTransacoesLista,
        AnaliseTransacoesContaLista $analiseTransacoesContaLista,
        AnaliseAgenciaLista  $analiseAgenciaLista,
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

            $analiseTransacoesContaLista->setAno($analise->ano);
            $analiseTransacoesContaLista->setMes($analise->mes);
            $analiseConta = $analiseTransacoesContaLista->dataProvider($request);

            $analiseAgenciaLista->setAno($analise->ano);
            $analiseAgenciaLista->setMes($analise->mes);
            $analiseAgencia  =  $analiseAgenciaLista->dataProvider($request);

            if (count(($analiseTransacoes)) == 0) {
                $analiseTransacoes = null;
            }
            if (count(($analiseConta)) == 0) {
                $analiseConta = null;
            }

            if (count(($analiseAgencia)) == 0) {
                $analiseAgencia = null;
            }
        }

        return $this->renderForm('analise/index.html.twig', [
            'form' => $form,
            'analiseTransacoes' => $analiseTransacoes,
            'analiseConta' => $analiseConta,
            'analiseAgencia' => $analiseAgencia
        ]);
    }
}
