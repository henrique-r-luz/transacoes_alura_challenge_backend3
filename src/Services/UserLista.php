<?php

namespace App\Services;

use App\Entity\User;
use Knp\Component\Pager\PaginatorInterface;
use Doctrine\Persistence\ManagerRegistry;

class UserLista
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
            5
        );
    }


    public function dataProvider($request)
    {
        $query = $this->doctrine->getRepository(User::class)->findBy([], ['nome' => 'ASC']);
        return $this->paginate(
            $query,
            $request
        );
    }
}
