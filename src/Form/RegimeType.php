<?php

namespace App\Form;

use Assert\Length;
use App\Entity\Plat;
use App\Entity\Regime;
use App\Repository\PlatRepository;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\RangeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Vich\UploaderBundle\Form\Type\VichImageType;

class RegimeType extends AbstractType
{
    private $token;
    public function __construct(TokenStorageInterface $token)
    {
        $this->token = $token;
    }
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            
            ->add('nomRegime' , TextType::class , [
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
            ->add('duree' , RangeType::class , [
                'attr' => [
                    'class'=> 'form-range',
                    'oninput'=>'this.nextElementSibling.value = this.value',
                    'min'=>'1',
                    'max'=>'50'
                ],
                'label'=> 'Durée (En Semaines)',
                'label_attr' => [
                    'class'=>'form-label mt-4'  
                ],
                'constraints' =>[
                    new Assert\LessThan(50),
                    new Assert\Positive()
                ]

            ])

            
            ->add('type' , TextType::class , [
                'attr' => [
                    'class'=> 'form-control',
                    'minlength'=>'2',
                    'maxlenth'=>'50'
                ],
                'label'=> 'Type',
                'label_attr' => [
                    'class'=>'form-label mt-4'  
                ],
                'constraints' =>[
                    new Assert\Length(['min'=>2,'max'=>50]),
                    new Assert\NotBlank()
                ]

            ])
            ->add('isFavorite' , CheckboxType::class , [
                'attr' => [
                    'class'=> 'form-check',
                ],
                'label'=> 'Favoris ?',
                'label_attr' => [
                    'class'=>'form-label mt-4'  
                ],
                'constraints' =>[
                    new Assert\NotNull()
                ]

            ])
            ->add('isPublic' , CheckboxType::class , [
                'attr' => [
                    'class'=> 'form-check',
                ],
                'label'=> 'Publique ?',
                'label_attr' => [
                    'class'=>'form-label mt-4'  
                ],
                

            ])

            ->add('imageFile', VichImageType::class,[
                
                'label'=>'Image de le régime',
                'label_attr'=>[
                    'class'=> 'form-label mt-4'
                    
                ]
                
            ])

            ->add('menu', EntityType::class , [
            'class'=>Plat::class,
            'query_builder' => function(PlatRepository $er){
                return $er->createQueryBuilder('i')
                ->where('i.user=:user')
                    ->orderBy('i.nomPlat','ASC')
                    ->setParameter('user',$this->token->getToken()->getUser());
            },
            'label'=> 'Les Plats',
            'label_attr' => [
                'class'=>'form-label mt-4 '  
            ],

            'choice_label' => 'nomplat',
            'multiple'=>true,
            'expanded'=>true
            ]
            )
            ->add('submit',SubmitType::class,[
                'attr' =>[
                    'class'=>'btn btn-dark mt-4'
                ],
                'label'=>'Modifier mon régime'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Regime::class,
        ]);
    }
}
