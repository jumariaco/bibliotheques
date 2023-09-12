<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;

/**
 * @extends ServiceEntityRepository<User>
 *
 * @implements PasswordUpgraderInterface<User>
 *
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', $user::class));
        }

        $user->setPassword($newHashedPassword);
        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();
    }


   /** This methode finds all users, listing by alphabetical list of mails
    * @return User[] Returns an array of User objects
    */
   public function findAll(): array
   {
       return $this->createQueryBuilder('u')
           ->andWhere('u.id IS NOT null')
           ->orderBy('u.email', 'ASC')
           ->getQuery()
           ->getResult()
       ;
   }

  /** This methode finds users containing a keyword, listing by alphabetical list of mails
    * @param string @keyword THe keyword to search for 
    * @return User[] Returns an array of User objects
    */
    public function findKeyword(string $keyword): array
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.roles LIKE :keyword')
            ->setParameter('keyword', "%$keyword%")
            ->orderBy('u.email', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

  /** This methode finds disabled users, listing by alphabetical list of mails
    * @return User[] Returns an array of User objects
    */
    public function findDisabled(string $keyword): array
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.actif LIKE :keyword')
            ->setParameter('keyword', "%$keyword%")
            ->orderBy('u.email', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }
    
//    /**
//     * @return User[] Returns an array of User objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('u.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?User
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
