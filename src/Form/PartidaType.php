<?php

namespace App\Form;

use App\Entity\Partida;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PartidaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('puntsBlanques')
            ->add('puntsNegres')
            ->add('numeroTaula')
            ->add('bye')
            ->add('pecesBlanques')
            ->add('pecesNegres')
            ->add('ronda')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Partida::class,
        ]);
    }
}
