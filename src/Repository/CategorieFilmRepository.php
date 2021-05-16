<?php

namespace App\Repository;

use App\Entity\CategorieFilm;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CategorieFilm|null find($id, $lockMode = null, $lockVersion = null)
 * @method CategorieFilm|null findOneBy(array $criteria, array $orderBy = null)
 * @method CategorieFilm[]    findAll()
 * @method CategorieFilm[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategorieFilmRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CategorieFilm::class);
    }

    // /**
    //  * @return CategorieFilm[] Returns an array of CategorieFilm objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?CategorieFilm
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
    public function stat(){
        $rawSql = "SELECT COUNT(f.id) as nb,c.type FROM categorie_film c JOIN film f on f.categorie_id=c.id GROUP BY c.id";

        $stmt = $this->getEntityManager()->getConnection()->prepare($rawSql);
        $stmt->execute([]);

        return $stmt->fetchAll();
    }
    public function tri(){
        return $this->createQueryBuilder('r')
            ->orderBy('r.type','ASC')
            ->getQuery()->getResult();
    }
}
