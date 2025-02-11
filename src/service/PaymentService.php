<?php

namespace App\Service;

use App\Service\AbstractService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

/*
 * Classe PaymentService dédiée à la gestion du paiement des abonnements des utilisateurs
 */

class PaymentService extends AbstractService
{
    public function __construct(private ParameterBagInterface $params, private EntityManagerInterface $em)
    {
        $this->params = $params;
        $this->em = $em;
    }

    public function getPayment(): void
    {
        $this->params->get('STRIPE_SK');
    }
}