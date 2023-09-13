<?php

namespace App\Repository;

use App\Entity\Emprunt;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Emprunt>
 *
 * @method Emprunt|null find($id, $lockMode = null, $lockVersion = null)
 * @method Emprunt|null findOneBy(array $criteria, array $orderBy = null)
 * @method Emprunt[]    findAll()
 * @method Emprunt[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EmpruntRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Emprunt::class);
    }

    /** This method finds 10 last borrowings at the chronological level,sorting in descending ordre of borrowing date 
     * @return Emprunt[] Returns an array of Emprunt objects
     */
    public function findByLast10(): array
    {
       return $this->createQueryBuilder('e')
           ->orderBy('e.dateEmprunt', 'DESC')
           ->setMaxResults(10)
           ->getQuery()
           ->getResult()
       ;
    }

    /** This method finds borrowings depends to an id borrower,sorting in ascending order of borrowing date
     * @param Emprunteur $emprunteur The borrower for which we want to find the borrowings 
     * @return Emprunt[] Returns an array of Emprunt objects
     */
    public function findByEmprunteur($emprunteur): array
    {
        return $this->createQueryBuilder('e')
            ->innerJoin('e. emprunteur', 'emp')
            ->andWhere('emp = :emprunteur')
            ->setParameter('emprunteur', $emprunteur)
            ->orderBy('e.dateEmprunt', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    /** This method finds borrowings depends to an id book,sorting in descending order of borrowing date
     * @param Livre $livre The book for which we want to find the borrowings 
     * @return Emprunt[] Returns an array of Emprunt objects
     */
    public function findByLivre($livre): array
    {
        return $this->createQueryBuilder('e')
            ->innerJoin('e.livre', 'l')
            ->andWhere('l = :livre')
            ->setParameter('livre', $livre)
            ->orderBy('e.dateEmprunt', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }

    /** This method finds 10 last return borrowings,sorting in descending ordre of borrowing date 
     * @return Emprunt[] Returns an array of Emprunt objects
     */
    public function findByRetour10(): array
    {
       return $this->createQueryBuilder('e')
           ->orderBy('e.dateRetour', 'DESC')
           ->setMaxResults(10)
           ->getQuery()
           ->getResult()
       ;
    }

     /** This method finds 10 last return borrowings,sorting in descending ordre of borrowing date 
      * @return Emprunt[] Returns an array of Emprunt objects
      */
    public function findByNonRetournes10(): array
    {
        return $this->createQueryBuilder('e')
            ->andWhere ('e.dateRetour IS null')
            ->orderBy('e.dateEmprunt', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

//    /**
//     * @return Emprunt[] Returns an array of Emprunt objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('e.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Emprunt
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
