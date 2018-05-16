<?php

namespace App\Entity\User;

use App\Entity\Team;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\User\AdminUserRepository")
 */
class AdminUser extends BaseUser
{
    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Team", mappedBy="mentors")
     */
    private $teams;

    public function __construct()
    {
        parent::__construct();
        $this->setRole(self::ROLE_ADMIN);
        $this->teams = new ArrayCollection();
    }

    /**
     * @return Collection|Team[]
     */
    public function getTeams(): Collection
    {
        return $this->teams;
    }

    public function addTeam(Team $team): self
    {
        if (!$this->teams->contains($team)) {
            $this->teams[] = $team;
            $team->addMentor($this);
        }

        return $this;
    }

    public function removeTeam(Team $team): self
    {
        if ($this->teams->contains($team)) {
            $this->teams->removeElement($team);
            $team->removeMentor($this);
        }

        return $this;
    }
}
