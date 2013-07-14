<?php

namespace Pfmgr\Repository;

use Doctrine\ORM\EntityRepository;

class AccountTransaction extends EntityRepository
{
    public function findTransactionsByUser($userId)
    {
        $query = $this->getEntityManager()->createQuery("
            SELECT u,a,t
            FROM \Pfmgr\Entity\AccountTransaction t
            JOIN t.account a
            JOIN a.user u
            WHERE u.id = ?1");
        $query->setParameter(1, $userId);
        $results = $query->getResult();

        return $results;
    }
}