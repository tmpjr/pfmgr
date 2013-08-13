<?php

namespace Pfmgr\Repository;

use Doctrine\ORM\EntityRepository;

class User extends EntityRepository
{
    public function findUserByEmail($email)
    {
        return $this->findOneBy(array('email' => $email));
    }
}