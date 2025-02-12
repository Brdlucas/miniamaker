<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'row_attr' => ['class' => 'm-3'],
                "label" => 'Votre addresse e-mail',
                'label_attr' => [
                    'class' => 'form-label',
                ],
                'attr' => [
                    'placeholder' => 'example@example.com',
                    'class' => 'form-control',
                ],
            ])
            ->add('plainPassword', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller

                'mapped' => false,
                'attr' => ['autocomplete' => 'new-password'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a password',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
            ])
            ->add('plainPassword', RepeatedType::class, [
                'label' => 'Mot de passe',
                'label_attr' => ['class' => 'form-label'],
                'type' => PasswordType::class, // avec quoi tu es associé à la répétition
                'invalid_message' => 'Le mot de passe doivent être identiques.',
                'mapped' => false,
                'attr' => ['class' => 'form-control'],
                'first_options' => ['label' => 'Mot de passe', 'label_attr' => ['class' => 'form-label'], 'attr' => ['class' => 'form-control'], 'row_attr' => ['class' => 'm-3'],],
                'second_options' => ['label' => 'Confirmer le mot de passe', 'label_attr' => ['class' => 'form-label'], 'attr' => ['class' => 'form-control'], 'row_attr' => ['class' => 'm-3'],],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a password',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
            ])
            ->add('isMajor', CheckboxType::class, [
                'row_attr' => ['class' => 'form_check m-3'],
                'label_attr' => ['class' => 'form-check-label'],
                'attr' => ['class' => 'form-check-input'],
                'label' => 'Je confirme être majeur',
                'constraints' => [
                    new IsTrue([
                        'message' => 'Vous devez être majeur pour vous inscrire',
                    ]),
                ],
            ])
            ->add('isTerms', CheckboxType::class, [
                'row_attr' => ['class' => 'form_check m-3'],
                'label_attr' => ['class' => 'form-check-label'],
                'attr' => ['class' => 'form-check-input'],
                'label' => 'J\'accepte les termes',
                'constraints' => [
                    new IsTrue([
                        'message' => 'Vous devez accepter les termes CGU pour vous inscrire',
                    ]),
                ],
            ])
            ->add('isGpdr', CheckboxType::class, [
                'row_attr' => ['class' => 'form_check m-3'],
                'label_attr' => ['class' => 'form-check-label'],
                'attr' => ['class' => 'form-check-input'],
                'label' => 'J\'ai lu la politique RGPD',
                'constraints' => [
                    new IsTrue([
                        'message' => 'Vous devez notre politique RGPD pour vous inscrire',
                    ]),
                ],
            ])
            ->add('submit', SubmitType::class, [
                'row_attr' => ['class' => 'm-3'],
                "label" => 'S\'inscrire',
                'attr' => [
                    'class' => 'btn btn-primary',
                ],
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