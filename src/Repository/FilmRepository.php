<?php

namespace App\Repository;

use App\Entity\Film;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Film|null find($id, $lockMode = null, $lockVersion = null)
 * @method Film|null findOneBy(array $criteria, array $orderBy = null)
 * @method Film[]    findAll()
 * @method Film[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FilmRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Film::class);
    }

    public function getByCategory(int $idCategory)
    {
        return $this->createQueryBuilder('f')
            ->where('f.categorie = :id')
            ->setParameter('id', $idCategory)
            ->getQuery()->getResult();
    }
    // /**
    //  * @return Film[] Returns an array of Film objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('f.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Film
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
    public function triParTitre()
    {
        return $this->createQueryBuilder('t')
            ->orderBy('t.titre', 'ASC')
            ->getQuery()->getResult();
    }

    public function triParDate()
    {
        return $this->createQueryBuilder('t')
            ->orderBy('t.datesortie', 'ASC')
            ->getQuery()->getResult();
    }

    public function triParNote()
    {
        return $this->createQueryBuilder('t')
            ->orderBy('t.note', 'DESC')
            ->getQuery()->getResult();
    }

}
