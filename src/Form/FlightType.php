<?php

namespace App\Form;

use App\Entity\City;
use App\Entity\Flight;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FlightType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('price', NumberType::class, [
                'label' => 'Prix du vol'
            ])
            ->add('schedule', TimeType::class, [
                'label' => 'Horaires',
                'hours' => range(7,21),
                'minutes' => [00, 15, 30, 45]
            ])
            ->add('reduction', CheckboxType::class, [
                'label' => 'Reduction 5% ?'
            ])
            ->add('seat', NumberType::class, [
                'label'=> 'Places disponibles'
            ])
            ->add(
                'departure',
                EntityType::class,
                [
                    'class' => City::class,
                    'choice_label' => 'name'
                ]
            )
            ->add(
                'arrival',
                EntityType::class,
                [
                    'class' => City::class,
                    'choice_label' => 'name'
                ]
            );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Flight::class,
            'required' => false
        ]);
    }
}
