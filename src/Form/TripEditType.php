<?php

namespace App\Form;

use App\Entity\Place;
use App\Entity\Trip;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TripEditType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('startDateTime',DateType::class,[
                'html5' =>true,
                'widget' => 'single_text'
            ])
            ->add('duration')
            ->add('deadline',DateType::class,[
                'html5' =>true,
                'widget' => 'single_text'
            ])
            ->add('maxRegistration')
            ->add('informations')
            ->add('place', EntityType::class, [
                'class' => Place::class,
                'choice_label' => 'name'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Trip::class,
        ]);
    }
}
