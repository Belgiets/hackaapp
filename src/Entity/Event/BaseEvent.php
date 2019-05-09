<?php


namespace App\Entity\Event;

use Knp\DoctrineBehaviors\Model\Timestampable\Timestampable;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class BaseEvent
 * @package App\Entity\Event
 *
 * @ORM\Entity(repositoryClass="App\Repository\Event\BaseEventRepository")
 *
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="discriminator", type="string")
 * @ORM\DiscriminatorMap({
 *     "base_event" = "BaseEvent",
 *     "hackathon" = "HackathonEvent",
 *     "meetup" = "MeetupEvent"
 * })
 */
class BaseEvent
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
    private $title;

    /**
     * @ORM\Column(type="date")
     */
    private $startDate;

    /**
     * @ORM\Column(type="date")
     */
    private $endDate;

    public function getId()
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->startDate;
    }

    public function setStartDate($startDate): self
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->endDate;
    }

    public function setEndDate($endDate): self
    {
        $this->endDate = $endDate;

        return $this;
    }

    public function __toString()
    {
        return $this->title;
    }
}