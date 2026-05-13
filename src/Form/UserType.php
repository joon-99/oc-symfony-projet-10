<?php

namespace App\Form;

use App\Entity\User;
use App\Enum\ContractEnum;
use App\Form\DataTransformer\StringToContractEnumTransformer;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('lastName', TextType::class, [
                'label' => 'Nom',
            ])
            ->add('firstName', TextType::class, [
                'label' => 'Prénom',
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email',
            ])
            ->add('hiredOn', DateType::class, [
                'label' => "Date d'entrée",
                'attr' => [
                    'lang' => 'fr-FR',
                ]
            ])
            ->add('status', ChoiceType::class, [
                'choices' => array_combine(ContractEnum::values(), ContractEnum::values()),
                'placeholder' => 'Choose a contract',
            ])
            ->add('role', ChoiceType::class, [
                'choices' => [
                    'Collaborateur' => 'ROLE_EMPLOYEE',
                    'Chef de projet' => 'ROLE_ADMIN',
                ],
                'label' => 'Rôle',
                'placeholder' => 'Choisissez un rôle',
                'required' => true,
            ])
        ;

        // convert between submitted string and ContractEnum instance
        $builder->get('status')->addModelTransformer(new StringToContractEnumTransformer());
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
