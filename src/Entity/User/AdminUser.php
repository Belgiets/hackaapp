<?php

namespace App\Entity\User;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\User\AdminUserRepository")
 */
class AdminUser extends BaseUser
{
    public function __construct()
    {
        parent::__construct();
        $this->setRole(self::ROLE_ADMIN);
    }
}
