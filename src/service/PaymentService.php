<?php

namespace App\Service;

use App\Entity\Subscription;
use App\Entity\User;
use App\Repository\SubscriptionRepository;
use Stripe\Stripe;
use App\Service\AbstractService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

/*
 * Classe PaymentService dédiée à la gestion du paiement des abonnements des utilisateurs
 */

class PaymentService extends AbstractService
{
    public function __construct(private ParameterBagInterface $params, private EntityManagerInterface $em, private SubscriptionRepository $sr)
    {
        $this->params = $params;
        $this->sr = $sr;
        $this->em = $em;
    }

    public function setPayment(User $user, int $amount): void
    {
        Stripe::setApiKey($this->params->get('STRIPE_SK'));

        $subscription = new Subscription();
        $subscription
            ->setClient($user)
            ->setAmount($amount)
            ->setFrequency($amount > 99 ? $this->params->get('STRIPE_SUB_ANNUALLY') : $this->params->get('STRIPE_SUB_MONTHLY'))
        ;

        dd($subscription);
    }
}