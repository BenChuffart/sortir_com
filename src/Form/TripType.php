<?php

namespace App\Form;

use App\Entity\Trip;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TripType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name',TextType ::class,[
                'label' => 'Title'
            ])
            ->add('startDateTime',DateTimeType::class,[
                'html5' =>true,
                'widget' => 'single_text'
            ])
            ->add('duration', NotBlank::class)
            ->add('deadline', DateType::class)
            ->add('maxRegistration',RangeType::class)
            ->add('informations', NotBlank::class)
            ->add('users')
            ->add('creator')
            ->add('campus')
            ->add('status')
            ->add('place')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Trip::class,
        ]);
    }
}
