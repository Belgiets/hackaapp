<?php

namespace App\Entity;

use App\Entity\User\AdminUser;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Model\Timestampable\Timestampable;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TeamRepository")
 */
class Team
{
    use Timestampable;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Event", inversedBy="teams")
     */
    private $event;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Participant", mappedBy="team")
     */
    private $participants;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\User\AdminUser", inversedBy="teams")
     */
    private $mentors;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $idea;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $place;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isAwardee;

    public function __construct()
    {
        $this->participants = new ArrayCollection();
        $this->mentors = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

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

    /**
     * @return Collection|Participant[]
     */
    public function getParticipants(): Collection
    {
        return $this->participants;
    }

    public function addParticipant(Participant $participant): self
    {
        if (!$this->participants->contains($participant)) {
            $this->participants[] = $participant;
            $participant->setTeam($this);
        }

        return $this;
    }

    public function removeParticipant(Participant $participant): self
    {
        if ($this->participants->contains($participant)) {
            $this->participants->removeElement($participant);
            // set the owning side to null (unless already changed)
            if ($participant->getTeam() === $this) {
                $participant->setTeam(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->name;
    }

    /**
     * @return Collection|AdminUser[]
     */
    public function getMentors(): Collection
    {
        return $this->mentors;
    }

    public function addMentor(AdminUser $mentor): self
    {
        if (!$this->mentors->contains($mentor)) {
            $this->mentors[] = $mentor;
        }

        return $this;
    }

    public function removeMentor(AdminUser $mentor): self
    {
        if ($this->mentors->contains($mentor)) {
            $this->mentors->removeElement($mentor);
        }

        return $this;
    }

    public function getIdea(): ?string
    {
        return $this->idea;
    }

    public function setIdea(?string $idea): self
    {
        $this->idea = $idea;

        return $this;
    }

    public function getPlace(): ?string
    {
        return $this->place;
    }

    public function setPlace(?string $place): self
    {
        $this->place = $place;

        return $this;
    }

    public function getIsAwardee(): ?bool
    {
        return $this->isAwardee;
    }

    public function setIsAwardee(?bool $isAwardee): self
    {
        $this->isAwardee = $isAwardee;

        return $this;
    }
}
