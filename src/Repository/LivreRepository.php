<?php

namespace App\Repository;

use App\Entity\Livre;
use App\Entity\Auteur;
use App\Entity\Genre;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Livre>
 *
 * @method Livre|null find($id, $lockMode = null, $lockVersion = null)
 * @method Livre|null findOneBy(array $criteria, array $orderBy = null)
 * @method Livre[]    findAll()
 * @method Livre[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LivreRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Livre::class);
    }

   /**This methode finds all books, listing by alphabetical list
    * @return Livre[] Returns an array of Livre objects
    */
   public function findAll(): array
   {
       return $this->createQueryBuilder('l')
           ->andWhere('l.id IS NOT null')
           ->orderBy('l.titre', 'ASC')
           ->getQuery()
           ->getResult()
       ;
   }

     /** This methode finds title books containing a keyword, listing by alphabetical list
    * @param string @keyword THe keyword to search for 
    * @return Livre[] Returns an array of book objects
    */
    public function findKeyword(string $keyword): array
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.titre LIKE :keyword')
            ->setParameter('keyword', "%$keyword%")
            ->orderBy('l.titre', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

     /** This methode finds author ID books containing a keyword, listing by alphabetical list
     * This method finds books in link with author, by making inner joins with authors and with books.
     * @param Auteur $auteur The author for which we want to find the books
     * @return Livre[] Returns an array of books objects
     */
    public function findByAuteur($auteur): array
    {
       return $this->createQueryBuilder('l')
            ->innerJoin('l.auteur','a')
            ->andWhere('a = :auteur')
            ->setParameter('auteur', $auteur)
            ->orderBy('l.titre', 'ASC')
            ->getQuery()
            ->getResult()
       ;
    }

     /** This methode finds books containing a keyword for the genre, listing by alphabetical list
     * This method finds books in link with genre, by making inner joins with genres and with books.
     * @param Genre $genre The genre for which we want to find the books
     * @return Livre[] Returns an array of books objects
     */
    public function findByGenre($genre): array
    {
       return $this->createQueryBuilder('l')
            ->innerJoin('l.livreGenre','lg')
            ->innerJoin('lg.genre','g')
            ->andWhere('g = :genre')
            ->setParameter('genre', $genre)
            ->orderBy('l.titre', 'ASC')
            ->getQuery()
            ->getResult()
       ;
    }

//    /**
//     * @return Livre[] Returns an array of Livre objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('l')
//            ->andWhere('l.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('l.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Livre
//    {
//        return $this->createQueryBuilder('l')
//            ->andWhere('l.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
