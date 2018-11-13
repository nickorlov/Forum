<?php

namespace ForumBundle\Repository;

use Doctrine\ORM\EntityRepository;
use ForumBundle\Entity\User;

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

    /**
     * @param string $username
     * @return User
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findOneByUserName(string $username): User
    {
        return $this->getEntityManager()
            ->createQuery(
                'SELECT p FROM ForumBundle:User p WHERE p.username = :username'
            )->setParameter('username', $username)
            ->getSingleResult();
    }
}