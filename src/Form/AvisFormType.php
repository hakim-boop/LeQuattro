<?php

namespace App\Form;

use App\Entity\Avis;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;

class AvisFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'label' => 'Nom',
            ])

            ->add('prenom', TextType::class, [
                'label' => 'Prénom',
            ])
            
            ->add('email', EmailType::class, [
                'label' => 'Choisissez un email',
                'constraints' => [
                    new Email(),
                    new Length([
                        'min' => 4,
                        'max' => 180,
                    ]),
                ]
            ])
            
            ->add('content', TextType::class, [
                'label' => 'Sujet',
                'constraints' => [
                    new Length([
                        'min' => 4,
                        'max' => 100,
                    ]),
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Avis::class,
        ]);
    }
}
