<?php

namespace App\Form;


use App\Entity\Event;
use App\Entity\ProjectType;
use App\Entity\Team;
use App\Form\Model\PersonParticipantModel;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PersonParticipantFilterType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'employment',
                ChoiceType::class,
                [
                    'label' => 'Employed?',
                    'expanded' => true,
                    'choices' => [
                        'No matter' => null,
                        'Yes' => true,
                        'No' => false,
                    ],
                ]
            )
            ->add(
                'internship',
                ChoiceType::class,
                [
                    'label' => 'Want internship?',
                    'expanded' => true,
                    'choices' => [
                        'No matter' => null,
                        'Yes' => true,
                        'No' => false,
                    ],
                ]
            )
            ->add(
                'isNotified',
                ChoiceType::class,
                [
                    'label' => 'Notified?',
                    'expanded' => true,
                    'choices' => [
                        'No matter' => null,
                        'Yes' => true,
                        'No' => false,
                    ],

                ]
            )
            ->add(
                'isActive',
                ChoiceType::class,
                [
                    'label' => 'Active?',
                    'expanded' => true,
                    'choices' => [
                        'No matter' => null,
                        'Yes' => true,
                        'No' => false,
                    ],
                ]
            )
            ->add(
                'event',
                EntityType::class,
                [
                    'class' => Event::class,
                    'placeholder' => 'No matter',
                    'required' => false
                ]
            )
//            ->add(
//                'projectType',
//                EntityType::class,
//                [
//                    'class' => ProjectType::class,
//                    'placeholder' => 'No matter',
//                    'required' => false
//                ]
//            )
            ->add(
                'team',
                EntityType::class,
                [
                    'class' => Team::class,
                    'placeholder' => 'No matter',
                    'required' => false
                ]
            )
            ->add(
                'noTeam',
                ChoiceType::class,
                [
                    'label' => 'No team?',
                    'expanded' => true,
                    'choices' => [
                        'No matter' => null,
                        'Yes' => true,
                    ],
                ]
            )
            ->add(
                'hasPhoto',
                ChoiceType::class,
                [
                    'label' => 'Has photo?',
                    'expanded' => true,
                    'choices' => [
                        'No matter' => null,
                        'Yes' => true,
                        'No' => false
                    ],
                ]
            )
            ->add('pageRange',
                ChoiceType::class,
                [
                    'choices' => [
                        '20' => 20,
                        '50' => 50,
                        '100' => 100,
                        '500' => 500
                    ]
                ]
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => PersonParticipantModel::class,
                'csrf_protection' => false,
                'allow_extra_fields' => true,
            ]
        );
    }
}