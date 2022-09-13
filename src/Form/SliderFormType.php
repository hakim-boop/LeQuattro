<?php

namespace App\Form;

use App\Entity\Slider;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class SliderFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('photo', FileType::class, [
                'label' => 'Photo',
                'data_class' => null,
                'constraints' => [
                    new NotBlank(),
                    new Image([
                        'mimeTypes' => ['image/jpeg', 'image/png'],
                        'mimeTypesMessage' => 'Les seuls formats autorisés sont : .jpg, .png',
                        'maxSize' => '3M',
                        'maxSizeMessage' => 'Le poids du fichier ne doit pas depasser {{ limit }} {{ suffix }} => {{ name }}: {{ size }} {{ suffix }}'
                    ]),
                ],
                'help' => 'Fichiers autorisés: .jpg, .png'

            ])
            ->add('ordre', TextType::class, [
                'label' => 'Ordre'
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Ajouter',
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
            'data_class' => Slider::class,
            'allow_file_upload' => true,
            'photo' => null
        ]);
    }
}
