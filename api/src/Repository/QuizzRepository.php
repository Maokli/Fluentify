<?php

namespace App\Repository;

use App\Entity\Category;
use App\Entity\Language;
use App\Entity\Quizz;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Quizz>
 *
 * @method Quizz|null find($id, $lockMode = null, $lockVersion = null)
 * @method Quizz|null findOneBy(array $criteria, array $orderBy = null)
 * @method Quizz[]    findAll()
 * @method Quizz[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class QuizzRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Quizz::class);
    }

    public function save(Quizz $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Quizz $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function GetTotalQuizzCountByLanguages($value): ?Quizz
    {
        return $this->createQueryBuilder('u')
            ->select('u')
            ->innerJoin('u.language','l')
            ->getQuery()
            ->getResult();
    }
    public function findOneByID($id): ?Quizz
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.id = :val')
            ->setParameter('val', $id)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    public function findByLanguageid(int  $languageid): array
    {
        return $this->createQueryBuilder('q')
        ->join('q.language', 'l')
        ->andWhere('l.id = :languageId')
        ->setParameter('languageId', $languageid)
        ->getQuery()
        ->getResult();
    }
    public function findByCategory(int $categoryid):array
    {
        return $this->createQueryBuilder('q')
            ->join('q.category', 'l')
            ->andWhere('l.id = :categoryId')
            ->setParameter('categoryId', $categoryid)
            ->getQuery()
            ->getResult();
    }

//    /**
//     * @return Quizz[] Returns an array of Quizz objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('q')
//            ->andWhere('q.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('q.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }


//    public function findOneBySomeField($value): ?Quizz
//    {
//        return $this->createQueryBuilder('q')
//            ->andWhere('q.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
