<?php

namespace App\Controller;

use App\Entity\Subscription;
use App\Service\PaymentService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_USER')]
final class SubscriptionController extends AbstractController
{
    private Subscription $subscription;

    public function __construct(
        private EntityManagerInterface $em
    ) {
        $this->subscription = $this->getUser()->getSubscription();
    }

    #[Route('/subscription', name: 'app_subscription', methods: ['POST'])]
    public function subscription(Request $request, PaymentService $ps): RedirectResponse
    {
        try {

            if ($this->subscription == null || $this->subscription->isActive() === false) {
                $checkoutUrl = $ps->setPayment(
                    $this->getUser(),
                    intval($request->get('plan'))
                );
                return $this->redirectToRoute('app_subscription_check', ['link' => $checkoutUrl]);
                // return new RedirectResponse($checkoutUrl);
            }

            $this->addFlash('warning', "Vous êtes déjà abonné(e)");
            return $this->redirectToRoute('app_profile');
        } catch (\Exception $e) {
            $this->addFlash('error', 'Une erreur est survenue lors de la création du paiement');
            return $this->redirectToRoute('app_profile');
        }
    }

    #[Route('/subscription/check', name: 'app_subscription_check', methods: ['GET'])]
    public function check(Request $request): Response
    {
        // Logique de traitement du succès
        return $this->render('subscription/check.html.twig', [
            'link' => $request->get('link'),
        ]);
    }

    #[Route('/subscription/success', name: 'app_subscription_success', methods: ['GET'])]
    public function success(Request $request): Response
    {

        // Modification de la valeur isActive tout true pour l'abonnement
        if ($this->subscription) {
            $this->subscription->setIsActive(true);
            $this->em->persist($this->subscription);
            $this->em->flush();
        }

        // envoie une notification de réussite
        $this->addFlash('success', 'Votre abonnement a bien été pris en compte');
        // retour sur la page profile
        return $this->redirectToRoute('app_profile');
    }

    #[Route('/subscription/cancel', name: 'app_subscription_cancel', methods: ['GET'])]
    public function cancel(): Response
    {
        // envoie une notification d'erreur
        $this->addFlash('warning', 'Votre abonnement n\'a pas bien été pris en compte');
        // Logique de traitement de l'annulation
        return $this->redirectToRoute('app_profile');
    }
}
