<?php

namespace App\Form;

use App\Entity\Contact;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ContactFormType extends AbstractType
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
                'label' => 'Email',
                'constraints' => [
                    new Email(),
                    new Length([
                        'min' => 4,
                        'max' => 180,
                    ]),
                ]
            ])
            
            ->add('sujet', TextType::class, [
                'label' => 'Sujet',
                'constraints' => [
                    new Length([
                        'min' => 4,
                        'max' => 100,
                    ]),
                ]
            ])
            
            ->add('categorie', ChoiceType::class, [
                'label' => 'Catégorie',
                'choices' => [
                    'Chambre' => 'chambre',
                    'Restaurant' => 'restaurant',
                    'Spa' => 'spa',
                    'Sujet général' => 'sujet general',
                ],
            ])
            
            ->add('message', TextareaType::class, [
                'label' => 'Message',
            ])

            ->add('submit', SubmitType::class, [
                'label' => "Envoyer",
                'validate' => false,
                'attr' => [
                    'class' => 'd-block mx-auto my-3 col-4 btn btn-dark'
                ],
            ]);
        
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Contact::class,
        ]);
    }
}
