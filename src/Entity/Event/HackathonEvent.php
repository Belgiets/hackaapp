<?php

namespace App\Entity\Event;

use App\Entity\Participant\HackathonParticipant;
use App\Entity\Team;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Event\HackathonEventRepository")
 */
class HackathonEvent extends BaseEvent
{
    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Team", mappedBy="event")
     */
    private $teams;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Participant\HackathonParticipant", mappedBy="event")
     */
    private $participants;

    public function __construct()
    {
        $this->participants = new ArrayCollection();
        $this->teams = new ArrayCollection();
    }

    /**
     * @return Collection|Team[]
     */
    public function getTeams(): Collection
    {
        return $this->teams;
    }

    public function addTeam(Team $team): self
    {
        if (!$this->teams->contains($team)) {
            $this->teams[] = $team;
            $team->setEvent($this);
        }

        return $this;
    }

    public function removeTeam(Team $team): self
    {
        if ($this->teams->contains($team)) {
            $this->teams->removeElement($team);
            // set the owning side to null (unless already changed)
            if ($team->getEvent() === $this) {
                $team->setEvent(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|HackathonParticipant[]
     */
    public function getParticipants(): Collection
    {
        return $this->participants;
    }

    public function addParticipant(HackathonParticipant $participant): self
    {
        if (!$this->participants->contains($participant)) {
            $this->participants[] = $participant;
            $participant->setEvent($this);
        }

        return $this;
    }

    public function removeParticipant(HackathonParticipant $participant): self
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
