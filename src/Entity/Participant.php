<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Model\Timestampable\Timestampable;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ParticipantRepository")
 */
class Participant
{
    use Timestampable;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isActive;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Person", inversedBy="participants")
     * @ORM\JoinColumn(nullable=false)
     */
    private $person;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Event", inversedBy="participants")
     * @ORM\JoinColumn(nullable=false)
     */
    private $event;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Team", inversedBy="participants")
     * @ORM\JoinColumn(onDelete="SET NULL")
     */
    private $team;

    /**
     * @ORM\Column(type="string", length=32)
     */
    private $activationCode;

    /**
     * @ORM\OneToOne(
     *     targetEntity="Media",
     *      cascade={"persist", "remove"},
     *      fetch="EAGER",
     *      orphanRemoval=true
     *     )
     * @ORM\JoinColumn(name="activation_qr_id", referencedColumnName="id", onDelete="SET NULL")
     */
    private $activationQr;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isNotified;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\ProjectType", inversedBy="participants")
     * @ORM\JoinColumn(nullable=true)
     */
    private $projectType;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Feedback", mappedBy="participant", orphanRemoval=true)
     */
    private $feedbacks;

    public function __construct()
    {
        $this->isActive = false;
        $this->isNotified = false;
        $this->feedbacks = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function isActive(): ?bool
    {
        return $this->isActive;
    }

    /**
     * @return mixed
     */
    public function getIsActive()
    {
        return $this->isActive;
    }

    /**
     * @param mixed $isActive
     */
    public function setIsActive($isActive): void
    {
        $this->isActive = $isActive;
    }

    public function activate()
    {
        $this->isActive = true;

        return $this;
    }

    public function deactivate()
    {
        $this->isActive = false;

        return $this;
    }

    public function getPerson(): ?Person
    {
        return $this->person;
    }

    public function setPerson(?Person $person): self
    {
        $this->person = $person;

        return $this;
    }

    public function getEvent(): ?Event
    {
        return $this->event;
    }

    public function setEvent(?Event $event): self
    {
        $this->event = $event;

        return $this;
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

    public function getActivationCode(): ?string
    {
        return $this->activationCode;
    }

    public function setActivationCode(string $activationCode): self
    {
        $this->activationCode = $activationCode;

        return $this;
    }

    /**
     * @param Media $activationQr
     * @return Participant
     */
    public function setActivationQr(Media $activationQr)
    {
        $this->activationQr = $activationQr;

        return $this;
    }

    /**
     * @return Media
     */
    public function getActivationQr()
    {
        return $this->activationQr;
    }

    public function getIsNotified(): ?bool
    {
        return $this->isNotified;
    }

    public function setIsNotified(bool $isNotified): self
    {
        $this->isNotified = $isNotified;

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
}
