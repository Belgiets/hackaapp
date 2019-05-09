<?php

namespace App\Form;

use App\Entity\Feedback;
use App\Entity\BaseParticipant;
use App\Entity\User\AdminUser;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FeedbackType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('description', TextareaType::class, ['required' => true])
            ->add(
                'save',
                SubmitType::class,
                ['label' => 'Save', 'attr' => ['class' => 'btn btn-primary']]
            )
            ->addEventListener(
                FormEvents::POST_SUBMIT,
                function (FormEvent $event) use ($options) {
                    /** @var Feedback $feedback */
                    $feedback = $event->getData();
                    $user = $options['user'];
                    $participant = $options['participant'];

                    if ($feedback) {
                        $feedback->setMentor($user);
                        $feedback->setParticipant($participant);
                    }
                }
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Feedback::class,
            'participant' => BaseParticipant::class,
            'user' => AdminUser::class
        ]);
    }
}
