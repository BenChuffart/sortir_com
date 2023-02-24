<?php

namespace App\Form;

use App\Data\Filters;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FiltersType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('campus', ChoiceType::class,[
                'label' => 'Campus',
                'choices' => [
                    'Nantes' => 'Nantes',
                    'Rennes' => 'Rennes',
                    'Niort' => 'Niort'
                ],
                'multiple' => false
            ])
            ->add('nameTrip', TextType::class,[
                'label' => 'Name Trip',
                'attr' => [
                    'placeholder' => 'Search'
                ]
            ])
            ->add('startDateTime', DateType::class,[
                'html5' =>true,
                'widget' => 'single_text'
            ])
            ->add('deadline', DateType::class,[
                'html5' =>true,
                'widget' => 'single_text'
            ])
            ->add('trips', CheckboxType::class, [
                'label' => 'Trips where',
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
