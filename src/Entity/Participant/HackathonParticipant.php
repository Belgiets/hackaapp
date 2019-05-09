<?php

namespace App\Entity\Participant;

use App\Entity\Event\HackathonEvent;
use App\Entity\Feedback;
use App\Entity\ProjectType;
use App\Entity\Team;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 *
 * @ORM\Entity(repositoryClass="App\Repository\Participant\HackathonParticipantRepository")
 */
class HackathonParticipant extends BaseParticipant
{
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Team", inversedBy="participants")
     * @ORM\JoinColumn(onDelete="SET NULL")
     */
    private $team;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\ProjectType", inversedBy="participants")
     * @ORM\JoinColumn(nullable=true)
     */
    private $projectType;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Feedback", mappedBy="participant", orphanRemoval=true)
     */
    private $feedbacks;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Event\HackathonEvent", inversedBy="participants")
     * @ORM\JoinColumn(nullable=false)
     */
    private $event;

    public function __construct()
    {
        parent::__construct();
        $this->feedbacks = new ArrayCollection();
    }

    public function getTeam(): ?Team
    {
        return $this->team;
    }

    public function setTeam(?Team $team): self
    {
        $this->team = $team;

        return $this;
    }

    public function getProjectType(): ?ProjectType
    {
        return $this->projectType;
    }

    public function setProjectType(?ProjectType $projectType): self
    {
        $this->projectType = $projectType;

        return $this;
    }

    /**
     * @return Collection|Feedback[]
     */
    public function getFeedbacks(): Collection
    {
        return $this->feedbacks;
    }

    public function addFeedback(Feedback $feedback): self
    {
        if (!$this->feedbacks->contains($feedback)) {
            $this->feedbacks[] = $feedback;
            $feedback->setParticipant($this);
        }

        return $this;
    }

    public function removeFeedback(Feedback $feedback): self
    {
        if ($this->feedbacks->contains($feedback)) {
            $this->feedbacks->removeElement($feedback);
            // set the owning side to null (unless already changed)
            if ($feedback->getParticipant() === $this) {
                $feedback->setParticipant(null);
            }
        }

        return $this;
    }

    public function hasFeedbackByUser($user)
    {
        /** @var Feedback $feedback */
        foreach ($this->getFeedbacks()->toArray() as $feedback) {
            if ($feedback->getMentor()->getId() == $user->getId()) {
                return $feedback;
            }
        }

        return null;
    }

    public function getFeedbackId($user)
    {
        foreach ($this->getFeedbacks() as $feedback) {
            if ($feedback->getMentor() === $user) {
                return $feedback->getId();
            }
        }

        return null;
    }

    public function getEvent(): ?HackathonEvent
    {
        return $this->event;
    }

    public function setEvent(?HackathonEvent $event): self
    {
        $this->event = $event;

        return $this;
    }
}