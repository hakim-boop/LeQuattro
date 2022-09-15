<?php

namespace App\Form;

use App\Entity\Chambre;
use App\Entity\Category;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ChambreFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre', TextType::class, [
                'label' => 'Titre'
            ])
            ->add('descriptionCourte', TextType::class, [
                'label' => 'Sous-titre'
            ])
            ->add('descriptionLongue',  TextareaType::class, [
                'label' => 'Contenu'
            ])
            ->add('category', ChoiceType::class, [
                'label' => 'Catégorie',
                'choices' => [
                    'Classique' => 'classique',
                    'Confort' => 'confort', 
                    'Suite' => 'suite'
                ]
            ])
            ->add('photo', FileType::class, [
                'label' => 'Photo',
                'data_class' => null,
                'constraints' => [
                    new Image([
                        'mimeTypes' => ['image/jpeg', 'image/png'],
                        'mimeTypesMessage' => 'Les formats autorisés sont : .jpg, .png',
                        'maxSize' => '3M',
                        'maxSizeMessage' => "Le poids maximal du fichier est : {{ limit }} {{ suffix }} => {{ name }}: {{ size }} {{ suffix }}"
                    ]),
                ],
                'help' => 'Fichiers autorisés: .jpg, .png',
            ])
            ->add('prixJournalier', TextType::class, [
                'label' => 'Prix unitaire',
            ])
            ->add('submit', SubmitType::class, [
                'validate' => false,
                'attr' => [
                    'class' => 'd-block mx-auto btn btn-primary col-3'
                ],
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Chambre::class,
            'allow_file_upload' => true,
            'photo' => null
        ]);
    }
}
