<?php

namespace App\Controller;

use App\Form\UserFormType;
use App\Service\UploaderService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class UserController extends AbstractController
{
    public function __construct(private EntityManagerInterface $em) {}


    #[Route('/profile', name: 'app_profile', methods: ['GET', 'POST'])]
    public function index(Request $request, UploaderService $us, UserPasswordHasherInterface $passwordHasher): Response
    {
        $user = $this->getUser();
        $form = $this->createForm(UserFormType::class,  $user);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {

            $password = $passwordHasher->isPasswordValid(
                $user,
                $form->get('password')->getData() // Récupère le password
            );

            if ($password) { // Si le mot de passe est valide
                $image = $form->get('image')->getData(); // Récupère l'image
                if ($image != null) { // Si l'image est téléversée
                    $user->setImage( // Méthode de mutation de l'image
                        $us->uploadFile( // Méthode de téléversement
                            $image, // Image téléversée
                            $user->getImage() // Image actuelle
                        )
                    );
                }
                $this->em->persist($user);
                $this->em->flush();

                // Redirection avec flash message
                $this->addFlash('success', 'Votre profil à été mis à jour');
            } else {
                $this->addFlash('error', 'une erreur est survenue');
            }

            return $this->redirectToRoute('app_profile');
        }

        if ($this->getUser()->isVerified() == false) {
            $this->addFlash('danger', 'Valider votre adresse e-mail');
        }

        return $this->render('user/index.html.twig', [
            'userForm' => $form,
        ]);
    }

    #[Route('/complete', name: 'app_complete', methods: ['POST'])]
    public function complete(Request $request): Response
    {
        $data = $request->getPayload(); // on récupère les données du formulaire
        if (!empty($data->get('username')) && !empty($data->get('fullname'))) {
            // Enregistrer les données dans la base de données
            $user = $this->getUser(); // Ici on récupère l'utilisateur actuel
            $user
                ->setUsername($data->get('username')) // on met à jour username
                ->setFullname($data->get('fullname')) // on met à jour fullname
            ;
            $this->em->persist($user); // on persiste l'utilisateur
            $this->em->flush(); // on sauvegarde les modifications en base de données

            // Redirection avec flash message
            $this->addFlash('success', 'Votre profil est complété');
        } else {
            $this->addFlash('error', 'Vous devez remplir tous les champs');
        }
        return $this->redirectToRoute('app_profile');
    }
}
