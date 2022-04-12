<?php

namespace App\Form;

use App\Entity\Reservationevenement;
use App\Entity\Evenement;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class ReservationevenementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nomre')
            ->add('datere')
            ->add('idevent', EntityType::class , [
                'class'=> Evenement::class,
                'choice_label'=>'idEvent',
                'multiple'=>false,
                'expanded'=>false ,
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reservationevenement::class,
        ]);
    }
}
