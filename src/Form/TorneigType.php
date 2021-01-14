<?php

namespace App\Form;

use App\Entity\Torneig;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TorneigType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom')
            ->add('numeroByes')
            ->add('pais')
            ->add('numRondes')
            ->add('arbitre')
            ->add('llistaJugadors')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Torneig::class,
        ]);
    }
}
