<?php

namespace App\Form;

use App\Entity\Campus;
use App\Data\Filters;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FiltersType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('campus', EntityType::class,[
                'label' => 'Campus',
                'class' => Campus::class,
                'required' => false,
                'choice_label' => 'name',
            ])
            ->add('nameTrip', TextType::class,[
                'label' => 'Name Trip',
                'required' => false,
                'attr' => [
                    'placeholder' => 'Search'
                ]
            ])
            ->add('startDateTime', DateType::class,[
                'html5' =>true,
                'widget' => 'single_text',
                'required' => false
            ])
            ->add('deadline', DateType::class,[
                'html5' =>true,
                'widget' => 'single_text',
                'required' => false
            ])
            ->add('tripsOrganized', CheckboxType::class, [
                'label' => 'Trips where I organized',
                'required' => false,
            ])
            ->add('tripsRegisted', CheckboxType::class, [
                'label' => 'Trips where I registed',
                'required' => false,
            ])
            ->add('tripsNotRegisted', CheckboxType::class, [
                'label' => 'Trips wherec I not registed',
                'required' => false,
            ])
            ->add('tripsPassed', CheckboxType::class, [
                'label' => 'Trips passed',
                'required' => false,
            ])
            
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver) 
    {
        $resolver->setDefaults([
            'data_class' => Filters::class,
            'method' => 'GET',
            'csrf_protection' => false
        ]);
    }

    public function getBlockPrefix()
    {
        return '';
    }
}
