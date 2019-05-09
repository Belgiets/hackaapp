<?php

namespace App\Entity;

use App\Entity\Participant\HackathonParticipant;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\FeedbackRepository")
 */
class Feedback
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Participant\HackathonParticipant", inversedBy="feedbacks")
     * @ORM\JoinColumn(nullable=false)
     */
    private $participant;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User\BaseUser", inversedBy="feedbacks")
     * @ORM\JoinColumn(nullable=false)
     */
    private $mentor;

    /**
     * @ORM\Column(type="text", nullable=false)
     */
    private $description;

    public function getId()
    {
        return $this->id;
    }

    public function getParticipant(): ?HackathonParticipant
    {
        return $this->participant;
    }

    public function setParticipant(?HackathonParticipant $participant): self
    {
        $this->participant = $participant;

        return $this;
    }

    public function getMentor()
    {
        return $this->mentor;
    }

    public function setMentor($mentor): self
    {
        $this->mentor = $mentor;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $description
     */
    public function setDescription($description): void
    {
        $this->description = $description;
    }

    public function getOwner()
    {
        return $this->getMentor();
    }
}
