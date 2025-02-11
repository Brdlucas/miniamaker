<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

/*
 * Classe abstraite  dédiée au service
 */

abstract class AbstractService
{
    // protected ParameterBagInterface $params;
    // protected EntityManagerInterface $em;

    public function __construct(private ParameterBagInterface $params, private EntityManagerInterface $em)
    {
        $this->params = $params;
        $this->em = $em;
    }
}