<?php

namespace ZeplinBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * ImagesRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ImagesRepository extends EntityRepository
{

    public function findAllByUserId($userId){
        $qb = $this->createQueryBuilder('p')
            ->where('p.userId = :userId')
            ->setParameter('userId', $userId)
            ->getQuery();
        return $qb->execute();
    }
}