<?php

namespace App\Entity\User;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\User\SuperAdminUserRepository")
 */
class SuperAdminUser extends BaseUser
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    public function __construct()
    {
        parent::__construct();
        $this->setRole(self::ROLE_SUPER_ADMIN);
    }

    public function getId()
    {
        return $this->id;
    }
}
