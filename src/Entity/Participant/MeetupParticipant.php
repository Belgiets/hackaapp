<?php

namespace App\Entity\Participant;

use Doctrine\ORM\Mapping as ORM;
use App\Entity\Event\MeetupEvent;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Participant\MeetupParticipantRepository")
 */
class MeetupParticipant extends BaseParticipant
{
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Event\MeetupEvent", inversedBy="participants")
     * @ORM\JoinColumn(nullable=false)
     */
    private $event;

    public function getEvent(): ?MeetupEvent
    {
        return $this->event;
    }

    public function setEvent(?MeetupEvent $event): self
    {
        $this->event = $event;

        return $this;
    }
}
