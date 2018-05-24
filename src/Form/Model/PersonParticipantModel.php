<?php

namespace App\Form\Model;


use App\Entity\ProjectType;
use Doctrine\Common\Collections\Collection;

class PersonParticipantModel
{
    /**
     * @var boolean
     */
    private $employment;

    /**
     * @var boolean
     */
    private $internship;

    /**
     * @var boolean
     */
    private $isNotified;

    /**
     * @var boolean
     */
    private $isActive;

    /**
     * @var \App\Entity\Event
     */
    private $event;

    /**
     * @var ProjectType
     */
    private $projectType;

    /**
     * @var \App\Entity\Team
     */
    private $team;

    /**
     * @return bool
     */
    public function isEmployment()
    {
        return $this->employment;
    }

    /**
     * @param bool $employment
     */
    public function setEmployment(bool $employment): void
    {
        $this->employment = $employment;
    }

    /**
     * @return bool
     */
    public function isInternship()
    {
        return $this->internship;
    }

    /**
     * @param bool $internship
     */
    public function setInternship(bool $internship): void
    {
        $this->internship = $internship;
    }

    /**
     * @return bool
     */
    public function isNotified()
    {
        return $this->isNotified;
    }

    /**
     * @param bool $isNotified
     */
    public function setIsNotified(bool $isNotified): void
    {
        $this->isNotified = $isNotified;
    }

    /**
     * @return bool
     */
    public function isActive()
    {
        return $this->isActive;
    }

    /**
     * @param bool $isActive
     */
    public function setIsActive(bool $isActive): void
    {
        $this->isActive = $isActive;
    }

    /**
     * @return \App\Entity\Event
     */
    public function getEvent()
    {
        return $this->event;
    }

    /**
     * @param \App\Entity\Event $event
     */
    public function setEvent(\App\Entity\Event $event): void
    {
        $this->event = $event;
    }

    /**
     * @return \App\Entity\ProjectType
     */
    public function getProjectType()
    {
        return $this->projectType;
    }

    /**
     * @param \App\Entity\ProjectType $projectType
     */
    public function setProjectType(\App\Entity\ProjectType $projectType): void
    {
        $this->projectType = $projectType;
    }

    /**
     * @return \App\Entity\Team
     */
    public function getTeam()
    {
        return $this->team;
    }

    /**
     * @param \App\Entity\Team $team
     */
    public function setTeam(\App\Entity\Team $team): void
    {
        $this->team = $team;
    }
}