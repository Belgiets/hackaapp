<?php

namespace App\Form;

use App\Entity\HackathonEvent;
use App\Entity\Team;
use App\Entity\User\AdminUser;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TeamType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('place')
            ->add('idea')
            ->add('isAwardee')
            ->add('event', EntityType::class, [
                'class' => HackathonEvent::class,
                'choice_label' => 'title'
            ])
            ->add('mentors', EntityType::class, [
                'class' => AdminUser::class,
                'choice_label' => 'email',
                'multiple' => true,
                'expanded' => true
            ])
            ->add('save', SubmitType::class, ['label' => 'Save', 'attr' => ['class' => 'btn btn-primary']])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Team::class,
            'edit' => false,
        ]);
    }
}
