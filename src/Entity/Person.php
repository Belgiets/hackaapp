<?php

namespace App\Entity;

use App\Entity\Participant\BaseParticipant;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Model\Timestampable\Timestampable;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PersonRepository")
 */
class Person
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
    private $firstName;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $lastName;

    /**
     * @ORM\Column(type="string", length=254, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $phoneNumber;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Participant\BaseParticipant", mappedBy="person", orphanRemoval=true)
     */
    private $participants;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $studyAt;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $specialization;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $course;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $codeSample;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $internship;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $employment;

    /**
     * @ORM\OneToOne(
     *     targetEntity="App\Entity\Media",
     *     cascade={"persist", "remove"},
     *     fetch="EAGER",
     *     orphanRemoval=true
     * )
     */
    private $photo;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Technology", mappedBy="persons")
     */
    private $technologies;

    public function __construct()
    {
        $this->participants = new ArrayCollection();
        $this->technologies = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(string $phoneNumber): self
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    /**
     * @return Collection|BaseParticipant[]
     */
    public function getParticipants(): Collection
    {
        return $this->participants;
    }

    public function addParticipant(BaseParticipant $participant): self
    {
        if (!$this->participants->contains($participant)) {
            $this->participants[] = $participant;
            $participant->setPerson($this);
        }

        return $this;
    }

    public function removeParticipant(BaseParticipant $participant): self
    {
        if ($this->participants->contains($participant)) {
            $this->participants->removeElement($participant);
            // set the owning side to null (unless already changed)
            if ($participant->getPerson() === $this) {
                $participant->setPerson(null);
            }
        }

        return $this;
    }

    public function getStudyAt(): ?string
    {
        return $this->studyAt;
    }

    public function setStudyAt(?string $studyAt): self
    {
        $this->studyAt = $studyAt;

        return $this;
    }

    public function getSpecialization(): ?string
    {
        return $this->specialization;
    }

    public function setSpecialization(?string $specialization): self
    {
        $this->specialization = $specialization;

        return $this;
    }

    public function getCourse(): ?string
    {
        return $this->course;
    }

    public function setCourse(?string $course): self
    {
        $this->course = $course;

        return $this;
    }

    public function getCodeSample(): ?string
    {
        return $this->codeSample;
    }

    public function setCodeSample(?string $codeSample): self
    {
        $this->codeSample = $codeSample;

        return $this;
    }

    public function getInternship(): ?bool
    {
        return $this->internship;
    }

    public function setInternship(?bool $internship): self
    {
        $this->internship = $internship;

        return $this;
    }

    public function getEmployment(): ?bool
    {
        return $this->employment;
    }

    public function setEmployment(?bool $employment): self
    {
        $this->employment = $employment;

        return $this;
    }

    public function getPhoto(): ?Media
    {
        return $this->photo;
    }

    public function setPhoto(?Media $photo): self
    {
        $this->photo = $photo;

        return $this;
    }

    public function __toString()
    {
        return $this->firstName . ' ' . $this->lastName;
    }

    /**
     * @return Collection|Technology[]
     */
    public function getTechnologies(): Collection
    {
        return $this->technologies;
    }

    public function addTechnology(Technology $technology): self
    {
        if (!$this->technologies->contains($technology)) {
            $this->technologies[] = $technology;
            $technology->addPerson($this);
        }

        return $this;
    }

    public function removeTechnology(Technology $technology): self
    {
        if ($this->technologies->contains($technology)) {
            $this->technologies->removeElement($technology);
            $technology->removePerson($this);
        }

        return $this;
    }
}
