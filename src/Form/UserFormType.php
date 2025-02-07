<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Detail;
use App\Entity\Subscription;
use Symfony\Component\Form\AbstractType;
use Symfony\UX\Dropzone\Form\DropzoneType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class UserFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [])
            ->add('password', PasswordType::class, [])
            ->add('username', TextType::class, [])
            ->add('fullname', TextType::class, [])
            ->add('image', DropzoneType::class, [
                'mapped' => false, // Déconnexion du lien avec l'entité
                'constraints' => [
                    new Image([
                        'mimeTypes' => ['jpg', 'jpeg', 'png'],
                        'mimeTypesMessage' => 'L\'image doit être au format .jpg, jpeg ou png',
                    ]),
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Modifier mon profil',
                'attr' => ['class' => 'btn btn-primary']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
