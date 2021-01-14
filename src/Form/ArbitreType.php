<?php

namespace App\Form;

use App\Entity\Arbitre;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArbitreType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom')
            ->add('cognoms')
            ->add('pais')
            ->add('usuari')
            ->add('contrasenya')
            ->add('dni')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Arbitre::class,
        ]);
    }
}
