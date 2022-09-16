<?php

namespace App\Form;

use App\Entity\Commande;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

class CommandeFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('dateDebut', DateTimeType::class,[
                'label' => 'Date arrivée'
            ])
            ->add('dateFin', DateTimeType::class,[
                'label' => 'Date depart'
            ])
            ->add('prixTotal', TextType::class, [
                'label' => 'Prix total',
            ])
            ->add('prenom', TextType::class, [
                'label' => 'Prenom',
            ])
            ->add('nom', TextType::class, [
                'label' => 'Nom',
            ])
            ->add('telephone', TextType::class, [
                'label' => 'Telephone',
            ])
            ->add('email', TextType::class, [
                'label' => 'email',
            ])
            ->add('submit', SubmitType::class, [
                'label' => "Valider",
                'validate' => false,
                'attr' => [
                    'class' => 'd-block mx-auto btn btn-primary col-3'
                ],
            ])
            // ->add('chambre')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Commande::class,
        ]);
    }
}
