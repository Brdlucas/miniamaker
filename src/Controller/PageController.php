<?php

namespace App\Controller;

use App\Service\LoginHistoryService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\MailerInterface;

final class PageController extends AbstractController
{
    public function __construct(
        private LoginHistoryService $lhs,
        private MailerInterface $mailer
    ) {}
    #[Route('/', name: 'app_homepage', methods: ['GET'])]
    public function index(Request $request,): Response
    {
        if (!$this->getUser()) {
            return $this->render('page/lp.html.twig');
        } else {
            $requestArray = [
                "fromLogin" => $this->getParameter('APP_URL') . $this->generateUrl('app_login'),
                "referer" => $request->headers->get('referer'),
                "ip" => $request->getClientIp(),
                "userAgent" => $request->headers->get('user-agent'),
            ];

            // Lancement du LoginHistoryService s'il vient de la connexion
            if ($requestArray['fromLogin'] === $requestArray['referer']) {
                $this->lhs->addHistory($this->getUser(), $requestArray['userAgent'], $requestArray['ip']);

                $email = (new Email())
                    ->from('contact@miniamaker.com')
                    ->to($this->getUser()->getEmail())
                    ->subject('Connexion réussie à votre compte')
                    ->html('<p>Bonjour,Nous vous informons qu\'une connexion à votre compte a été détectée
                    <br><br>
                    Si vous n\'êtes pas à l\'origine de cette connexion, veuillez immédiatement réinitialiser votre mot de passe.
                    <br><br>
                    Cordialement, L\'équipe MiniaMaker</p>');

                // Envoi de l'email
                $this->mailer->send($email);
            }

            // Vérification du profil complet d'utilisateur
            if (!$this->getUser()->isComplete()) {
                return $this->render('user/complete.html.twig');
            }
            // renvoie sur la page d'acceuil
            return $this->render('page/homepage.html.twig');
        }
    }
}
