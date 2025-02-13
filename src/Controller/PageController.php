<?php

namespace App\Controller;

use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\Address;
use App\Service\LoginHistoryService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class PageController extends AbstractController
{
    public function __construct(private MailerInterface $mailer) {}

    #[Route('/', name: 'app_homepage', methods: ['GET'])]
    public function index(Request $request, LoginHistoryService $lhs): Response
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
                $lhs->addHistory($this->getUser(), $requestArray['userAgent'], $requestArray['ip']);
            }

            // Vérification du profil complet d'utilisateur
            if (!$this->getUser()->isComplete()) {
                return $this->render('user/complete.html.twig');
            }
            // envoie un email pour avertir le client qu'une connexion a eu lieu
            $email =
                (new Email())
                ->from(new Address('contact@miniamaker.fr', 'miniamaker'))
                ->to($this->getUser()->getEmail())
                ->subject('Please Confirm your Email')
                ->html('Bonjour,Nous vous informons qu\'une connexion à votre compte a été détectée  <br><br> Si vous n\'êtes pas à l\'origine de cette connexion, veuillez immédiatement réinitialiser votre mot de passe.<br><br> Cordialement, L\'équipe MiniaMaker');
            $this->mailer->send($email);
            // renvoie sur la page d'acceuil
            return $this->render('page/homepage.html.twig');
        }
    }
}