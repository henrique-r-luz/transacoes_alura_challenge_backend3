<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;

class Analise
{

    public int $mes;

    #[Assert\Length(
        min: 4,
        max: 4,
        minMessage: 'Número minimo de caracteres {{ limit }}',
        maxMessage: 'Número maximo de caracteres {{ limit }}',
    )]
    public int $ano;
}
