<?php

namespace App\Entity;

use App\Entity\User\AdminUser;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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
     * @ORM\ManyToOne(targetEntity="App\Entity\Participant", inversedBy="feedbacks")
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

    public function getParticipant(): ?Participant
    {
        return $this->participant;
    }

    public function setParticipant(?Participant $participant): self
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
}
