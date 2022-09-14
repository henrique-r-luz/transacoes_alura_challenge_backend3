<?php

namespace App\Controller;

use App\Entity\Analise;
use App\Form\AnaliseForm;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AnaliseController extends AbstractController
{
    #[Route(path: '/app/analise/index', name: 'analise_index')]
    public function index(Request $request)
    {
        $analise = new Analise();
        $form =  $this->createForm(AnaliseForm::class, $analise);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $analise = $form->getData();
            print_r($analise);
            exit();
        }

        return $this->renderForm('analise/index.html.twig', [
            'form' => $form,
            //'services' => $importLista->dataProvider($request)
        ]);
    }
}
