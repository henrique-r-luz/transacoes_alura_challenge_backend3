<?php

namespace App\Controller;

use App\Entity\ContaBancaria;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ContaBancariaController extends AbstractController
{
    #[Route('/conta/bancaria', name: 'app_conta_bancaria', methods: 'GET')]
    public function index(ManagerRegistry $doctrine): JsonResponse
    {
        $contabancoRepository = $doctrine->getManager()->getRepository(ContaBancaria::class);
        $contasBancarias = $contabancoRepository->findAll();
        return $this->json($contasBancarias);
    }
}
