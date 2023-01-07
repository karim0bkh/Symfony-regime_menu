<?php

namespace App\Form;

use App\Entity\Plat;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;



class PlatType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nomPlat' , TextType::class , [
                'attr' => [
                    'class'=> 'form-control',
                    'minlength'=>'2',
                    'maxlenth'=>'50'
                ],
                'label'=> 'Nom',
                'label_attr' => [
                    'class'=>'form-label mt-4'  
                ],
                'constraints' =>[
                    new Assert\Length(['min'=>2,'max'=>50]),
                    new Assert\NotBlank()
                ]

            ])
            ->add('cout', MoneyType::class , [
                'attr' => [
                    'class'=> 'form-control',
                    'min'=>'2',
                    'max'=>'200'
                ],
                'label'=> 'Cout',
                'label_attr' => [
                    'class'=>'form-label mt-4'  
                ],
                'constraints' =>[
                    new Assert\Positive(),
                    new Assert\LessThan(200)
                ]

            ])
            ->add('nbrCalories', NumberType::class , [
                'attr' => [
                    'class'=> 'form-control',
                    'min'=>'1',
                    'max'=>'250'
                ],
                'label'=> 'Calories',
                'label_attr' => [
                    'class'=>'form-label mt-4'  
                ],
                'constraints' =>[
                    new Assert\Positive(),
                    new Assert\LessThan(250)
                ]

            ])
            ->add('ingredients', TextareaType::class , [
                'attr' => [
                    'class'=> 'form-control',
                    'minlength'=>'10',
                    'maxlenth'=>'200'
                ],
                'label'=> 'Ingredients',
                'label_attr' => [
                    'class'=>'form-label mt-4'  
                ],
                'constraints' =>[
                    new Assert\Length(['min'=>10,'max'=>200]),
                    new Assert\NotBlank()
                ]

            ])
            ->add('submit',SubmitType::class,[
                'attr' =>[
                    'class'=>'btn btn-dark mt-4'
                ],
                'label'=>'Modifier mon plat'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Plat::class,
        ]);
    }
}
