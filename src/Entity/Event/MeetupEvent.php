<?php

namespace App\Entity\Event;

use App\Entity\Participant\MeetupParticipant;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * Class MeetupEvent
 * @package App\Entity\Event
 *
 * @ORM\Entity(repositoryClass="App\Repository\Event\MeetupEventRepository")
 */
class MeetupEvent extends BaseEvent
{
    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Participant\HackathonParticipant", mappedBy="event")
     */
    private $participants;

    public function __construct()
    {
        $this->participants = new ArrayCollection();
    }

    /**
     * @return Collection|MeetupParticipant[]
     */
    public function getParticipants(): Collection
    {
        return $this->participants;
    }

    public function addParticipant(MeetupParticipant $participant): self
    {
        if (!$this->participants->contains($participant)) {
            $this->participants[] = $participant;
            $participant->setEvent($this);
        }

        return $this;
    }

    public function removeParticipant(MeetupParticipant $participant): self
    {
        if ($this->participants->contains($participant)) {
            $this->participants->removeElement($participant);
            // set the owning side to null (unless already changed)
            if ($participant->getEvent() === $this) {
                $participant->setEvent(null);
            }
        }

        return $this;
    }
}