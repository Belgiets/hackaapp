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
     * @var boolean
     */
    private $noTeam;

    /**
     * @var integer
     */
    private $pageRange;

    /**
     * @var boolean
     */
    private $hasPhoto;

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

    public function setIsActive(bool $isActive): void
    {
        $this->isActive = $isActive;
    }

    public function getEvent()
    {
        return $this->event;
    }

    public function setEvent(\App\Entity\Event $event): void
    {
        $this->event = $event;
    }

    public function getProjectType()
    {
        return $this->projectType;
    }

    public function setProjectType(\App\Entity\ProjectType $projectType): void
    {
        $this->projectType = $projectType;
    }


    public function getTeam()
    {
        return $this->team;
    }

    public function setTeam(\App\Entity\Team $team): void
    {
        $this->team = $team;
    }

    public function isNoTeam(): ? bool
    {
        return $this->noTeam;
    }

    public function setNoTeam(bool $noTeam): void
    {
        $this->noTeam = $noTeam;
    }

    public function getPageRange()
    {
        return $this->pageRange;
    }

    public function setPageRange(int $pageRange): void
    {
        $this->pageRange = $pageRange;
    }

    public function hasPhoto(): ? bool
    {
        return $this->hasPhoto;
    }

    public function setHasPhoto(bool $hasPhoto): void
    {
        $this->hasPhoto = $hasPhoto;
    }
}