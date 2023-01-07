<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType ;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class InscriptionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email',EmailType::class , [
                'attr' => [
                    'class'=> 'form-control',
                    'minlength'=>'2',
                    'maxlenth'=>'180'
                ],
                'label'=> 'Email',
                'label_attr' => [
                    'class'=>'form-label mt-4'  
                ],
                'constraints' =>[
                    new Assert\Length(['min'=>2,'max'=>50]),
                    new Assert\NotBlank(),
                    new Assert\Email()
                ]

            ])
            ->add('password',RepeatedType::class, [
                'type' => PasswordType::class,
                'first_options' => [
                    'attr' => [
                        'class' => 'form-control'
                    ],
                    'label' => 'Mot de passe',
                    'label_attr' => [
                        'class' => 'form-label  mt-4'
                    ]
                ],
                'second_options' => [
                    'attr' => [
                        'class' => 'form-control'
                    ],
                    'label' => 'Confirmation du mot de passe',
                    'label_attr' => [
                        'class' => 'form-label  mt-4'
                    ]
                ],
                'invalid_message' => 'Les mots de passe ne correspondent pas.'
            ])

            ->add('nomPrenom',TextType::class , [
                'attr' => [
                    'class'=> 'form-control',
                    'minlength'=>'2',
                    'maxlenth'=>'50'
                ],
                'label'=> 'Nom&Prenom',
                'label_attr' => [
                    'class'=>'form-label mt-4'  
                ],
                'constraints' =>[
                    new Assert\Length(['min'=>2,'max'=>50]),
                    new Assert\NotBlank()
                ]

            ])
            ->add('pseudo',TextType::class , [
                'attr' => [
                    'class'=> 'form-control',
                    'minlength'=>'2',
                    'maxlenth'=>'50'
                ],
                'label'=> 'Pseudo',
                'label_attr' => [
                    'class'=>'form-label mt-4'  
                ],
                'constraints' =>[
                    new Assert\Length(['min'=>2,'max'=>50]),
                    new Assert\NotBlank()
                ]

            ])
            ->add('submit',SubmitType::class,[
                'attr'=>[
                    'class'=>'btn btn-dark mt-4'
                ]

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
