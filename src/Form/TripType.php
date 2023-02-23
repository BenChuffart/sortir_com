<?php

namespace App\Form;

use App\Entity\Trip;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
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
            ->add('startDateTime',DateType::class,[
                'html5' =>true,
                'widget' => 'single_text'
            ])
            ->add('duration',IntegerType::class,[
                'label' => 'Duration (min)'   
                 ])
            ->add('deadline', DateType::class,[
                'html5' =>true,
                'widget' => 'single_text'
            ])
            ->add('maxRegistration')
            ->add('informations')
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
