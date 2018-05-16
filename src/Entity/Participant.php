<?php

namespace App\Entity;

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

    public function __construct()
    {
        $this->isActive = false;
        $this->isNotified = false;
    }

    public function getId()
    {
        return $this->id;
    }

    public function isActive(): ?bool
    {
        return $this->isActive;
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
}
