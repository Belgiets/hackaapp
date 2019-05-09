<?php

namespace App\Entity\Participant;

use App\Entity\Media;
use App\Entity\Person;
use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Model\Timestampable\Timestampable;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Participant\BaseParticipantRepository")
 *
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="discriminator", type="string")
 * @ORM\DiscriminatorMap({
 *     "base_participant" = "BaseParticipant",
 *     "hackathon_participant" = "HackathonParticipant",
 *     "meetup_participant" = "MeetupParticipant"
 * })
 */
class BaseParticipant
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
     * @ORM\Column(type="string", length=32)
     */
    private $activationCode;

    /**
     * @ORM\OneToOne(
     *     targetEntity="App\Entity\Media",
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
     * @return BaseParticipant
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
}
