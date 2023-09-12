<?php

namespace App\Repository;

use App\Entity\Emprunteur;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Emprunteur>
 *
 * @method Emprunteur|null find($id, $lockMode = null, $lockVersion = null)
 * @method Emprunteur|null findOneBy(array $criteria, array $orderBy = null)
 * @method Emprunteur[]    findAll()
 * @method Emprunteur[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EmprunteurRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Emprunteur::class);
    }

   /**This methode finds all borrowers, listing by alphabetical list of lastname and firstname
    * @return Livre[] Returns an array of Livre objects
    */
    public function findAll(): array
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.id IS NOT null')
            ->orderBy('e.nom', 'ASC')
            ->orderBy('e.prenom', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    /** This method finds borrowers in link with user, by making inner joins with userss and with borrowers.
     * @param User $user The author for which we want to find the borrowers
     * @return Emprunteur[] Returns an array of borrowers objects
     */
    public function findByUser($user): array
    {
       return $this->createQueryBuilder('e')
            ->innerJoin('e.user','u')
            ->andWhere('u = :user')
            ->setParameter('user', $user)
            ->orderBy('e.nom', 'ASC')
            ->getQuery()
            ->getResult()
       ;
    }

    /** This methode finds lastname or firstname borrowers containing a keyword, listing by alphabetical list of lastname and firstname
    * @param string @keyword The keyword to search for 
    * @return Emprunteur[] Returns an array of borrowers objects
    */
    public function findKeyword(string $keyword): array
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.nom LIKE :keyword')
            ->orWhere('e.nom LIKE :keyword')
            ->setParameter('keyword', "%$keyword%")
            ->orderBy('e.nom', 'ASC')
            ->orderBy('e.prenom', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    /** This methode finds phonenumber borrowers containing a keyword, listing by alphabetical list of lastname and firstname
    * @param string @keyword The keyword to search for 
    * @return Emprunteur[] Returns an array of borrowers objects
    */
    public function findByKeyword(string $keyword): array
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.telephone LIKE :keyword')
            ->setParameter('keyword', "%$keyword%")
            ->orderBy('e.nom', 'ASC')
            ->orderBy('e.prenom', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }



//    /**
//     * @return Emprunteur[] Returns an array of Emprunteur objects
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

//    public function findOneBySomeField($value): ?Emprunteur
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
