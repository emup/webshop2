<?php

namespace App\Form;

use App\Entity\Factuur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FactuurType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('factuurdatum')
            ->add('vervaldatum')
            ->add('inzakeopdracht')
            ->add('korting')
            ->add('klantnummer')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Factuur::class,
        ]);
    }
}
