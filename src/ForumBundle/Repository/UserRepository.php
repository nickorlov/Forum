<?php

namespace ForumBundle\Repository;

use Doctrine\ORM\EntityRepository;

class UserRepository extends EntityRepository
{
    public function findAllOrderedByName()
    {
        return $this->getEntityManager()
            ->createQuery(
                'SELECT p FROM ForumBundle:User p ORDER BY p.name ASC'
            )
            ->getResult();
    }
}