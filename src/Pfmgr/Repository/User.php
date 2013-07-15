<?php

namespace Pfmgr\Repository;

use Doctrine\ORM\EntityRepository;

class User extends EntityRepository
{
    public function findUserByEmail($email)
    {
        return $this->findOneBy(array('email' => $email));

        // $query = $this->getEntityManager()->createQuery("
        //     SELECT u
        //     FROM \Pfmgr\Entity\User u
        //     WHERE u.email = ?1");
        // $query->setParameter(1, $email);
        // $result = $query->getResult();

        // return $results;
    }
}