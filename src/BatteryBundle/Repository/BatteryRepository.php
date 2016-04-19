<?php

namespace BatteryBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * Class BatteryRepository
 * @package BatteryBundle\Repository
 */
class BatteryRepository extends EntityRepository
{
    /**
     * Get statistic
     *
     * @return array
     */
    public function getStatistic()
    {
        return $this->createQueryBuilder('b')
            ->select('sum(b.count) as total, b.type')
            ->groupBy('b.type')
            ->getQuery()
            ->getResult();
    }

    /**
     * Remove all data
     *
     * @return mixed
     */
    public function removeAll()
    {
        return $this->createQueryBuilder('b')
            ->delete($this->getClassName())
            ->getQuery()
            ->execute();
    }
}
