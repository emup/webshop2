<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email')
            ->add('plainPassword', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
                'label' => 'Wachtwoord',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Voer een wachtwoord in',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Uw wachtwoord moet ten minste {{ limit }} karakters lang zijn',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
            ])
            ->add('btwnummer')
            ->add('naam')
            ->add('adres')
            ->add('postcode')
            ->add('plaats')
            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'label' => 'Accepteer voorwaarden',
                'constraints' => [
                    new IsTrue([
                        'message' => 'U moet de voorwaarden accepteren.',
                    ]),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
