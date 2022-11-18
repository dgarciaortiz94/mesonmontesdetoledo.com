<?php

namespace App\Repository\Notification;

use App\Entity\Notification\UserBlockedNotification;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<UserBlockedNotification>
 *
 * @method UserBlockedNotification|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserBlockedNotification|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserBlockedNotification[]    findAll()
 * @method UserBlockedNotification[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserBlockedNotificationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserBlockedNotification::class);
    }

    public function save(UserBlockedNotification $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(UserBlockedNotification $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return UserBlockedNotification[] Returns an array of UserBlockedNotification objects
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

//    public function findOneBySomeField($value): ?UserBlockedNotification
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
