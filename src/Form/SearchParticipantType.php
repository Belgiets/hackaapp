<?php

namespace App\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class SearchParticipantType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            'lastname',
            null,
            [
                'required' => true,
                'attr' => [
                    'placeholder' => 'Search by lastname',
                ],

            ]
        );
    }
}
