<?php

namespace App\Form;

use App\Entity\Participant;
use App\Entity\Person;
use App\Repository\PersonRepository;
use App\Service\CsvParser;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ParticipantType extends AbstractType
{

    /**
     * @var \App\Repository\PersonRepository
     */
    private $personRepository;

    /**
     * @var \App\Service\CsvParser
     */
    private $csvParser;

    /**
     * ParticipantType constructor.
     */
    public function __construct(
        PersonRepository $personRepository,
        CsvParser $csvParser
    ) {
        $this->personRepository = $personRepository;
        $this->csvParser = $csvParser;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('projectType')
            ->add('event')
            ->add('team')
            ->add('isActive')
            ->addEventListener(
                FormEvents::PRE_SET_DATA,
                function (FormEvent $event) use ($options) {
                    $form = $event->getForm();

                    if ($options['person_id'] !== null) {
                        $form->add(
                            'person',
                            EntityType::class,
                            [
                                'class' => Person::class,
                                'data' => $this->personRepository->findOneBy(
                                    ['id' => $options['person_id']]
                                ),
                            ]
                        );
                    } else {
                        $form->add('person');
                    }

                    $form->add(
                        'save',
                        SubmitType::class,
                        ['label' => 'Save', 'attr' => ['class' => 'btn btn-primary']]
                    );
                }
            )
            ->addEventListener(
                FormEvents::POST_SUBMIT,
                function (FormEvent $event) use ($options) {
                    if (($options['person_id'] !== null) && (!$options['edit'])) {
                        $person = $this->personRepository->findOneBy(
                            ['id' => $options['person_id']]
                        );
                        $participant = $event->getData();

                        $activationCode = $this->csvParser->generateCode(
                            $person->getEmail().$participant->getEvent()->getTitle()
                        );

                        $participant->setActivationCode($activationCode);
                    }
                }
            );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => Participant::class,
                'edit' => false,
                'person_id' => null,
            ]
        );
    }
}
