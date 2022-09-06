<?php

namespace App\Services;

use App\Entity\Import;
use Knp\Component\Pager\PaginatorInterface;
use Doctrine\Persistence\ManagerRegistry;

class ImportLista
{
    private $paginator;
    private $doctrine;

    public function __construct(
        PaginatorInterface $paginator,
        ManagerRegistry $doctrine
    ) {
        $this->paginator = $paginator;
        $this->doctrine = $doctrine;
    }

    private function paginate($query, $request)
    {
        return $this->paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            10
        );
    }


    public function dataProvider($request)
    {
        $query = $this->doctrine->getRepository(Import::class)->findBy([], ['data' => 'DESC']);
        return $this->paginate(
            $query,
            $request
        );
    }
}
