<?php

namespace App\Form;

use App\Entity\Task;
use App\Entity\User;
use App\Enum\TaskCategoryEnum;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class TaskType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title')
            ->add('description')
            ->add('deadline')
            ->add('category', ChoiceType::class, [
                'choices' => TaskCategoryEnum::cases(),
                'choice_value' => fn(?TaskCategoryEnum $c) => $c?->value,
                'choice_label' => fn(TaskCategoryEnum $c) => $c->name,
                'placeholder' => 'Choisissez une catégorie',
            ])
            ->add('users', EntityType::class, [
                'class' => User::class,
                'choice_label' => fn(User $user) => $user,
                'multiple' => true,
                'by_reference' => false,
                'required' => false,
            ])
        ;

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Task::class,
        ]);
    }
}
