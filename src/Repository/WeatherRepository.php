<?php

namespace App\Repository;

use App\Entity\Weather;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Weather|null find($id, $lockMode = null, $lockVersion = null)
 * @method Weather|null findOneBy(array $criteria, array $orderBy = null)
 * @method Weather[]    findAll()
 * @method Weather[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WeatherRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Weather::class);
    }

    // /**
    //  * @return Weather[] Returns an array of Weather objects
    //  */
    
    /**
     * Get max temperature
     *
     * @return void
     */
    public function getMaxTemp()
    {
        return $this->createQueryBuilder('s')
            ->select('max(s.temp) AS max_temp')
            ->getQuery()
            ->getSingleResult()
        ;
    }

    /**
     * Get min temperature
     *
     * @return void
     */
    public function getMinTemp()
    {
        return $this->createQueryBuilder('s')
            ->select('min(s.temp) AS min_temp')
            ->getQuery()
            ->getSingleResult()
        ;
    }

    /**
     * Get avg of temperatures
     *
     * @return void
     */
    public function getAvgTemp()
    {
        return $this->createQueryBuilder('s')
            ->select('avg(s.temp) AS avg_temp')
            ->getQuery()
            ->getSingleResult()
        ;
    }

    /**
     * Get count of all results
     *
     * @return void
     */
    public function getAllResults()
    {
        return $this->createQueryBuilder('s')
            ->select('count(s.id) as count')
            ->getQuery()
            ->getSingleResult()
        ;
    }

    /**
     * Get most popular city
     *
     * @return void
     */
    public function getMostPopularCity()
    {
        return $this->createQueryBuilder('s')
            ->select('s.name, count(s.id) as NUM')
            ->groupBy("s.name")
            ->addOrderBy('NUM', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getSingleResult()
        ;
    }
    
    /*
    public function findOneBySomeField($value): ?Weather
    {
        return $this->createQueryBuilder('w')
            ->andWhere('w.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
