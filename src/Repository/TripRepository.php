<?php

namespace App\Repository;

use App\Entity\Trip;
use App\Entity\User;
use App\Data\Filters;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Trip>
 *
 * @method Trip|null find($id, $lockMode = null, $lockVersion = null)
 * @method Trip|null findOneBy(array $criteria, array $orderBy = null)
 * @method Trip[]    findAll()
 * @method Trip[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TripRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Trip::class);
    }

    public function add(Trip $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    // FILTRES TOUT LES TRIPS EN LIEN DE LA RECHERCHE 
    public function findTrip(Filters $filters, User $user) : array{

    
        $query = $this -> createQueryBuilder('s');
                       /* -> andWhere ('s.name LIKE :name')
                        -> setParameter('name',"%{$name}%");*/

       if(!empty($filters -> campus))
       {
            $query = $query 
                ->andWhere('s.campus = :campus')
                ->setParameter('campus', $filters -> campus);
       }
       if(!empty($filters -> nameTrip))
       {
            $query = $query 
                ->andWhere('s.name LIKE :name')
                ->setParameter('name',"%{ $filters -> nameTrip}%");
       }
       if(!empty($filters -> startDateTime))
       {
            $query = $query 
                ->andWhere('s.startDateTime >= :startDateTime')
                ->setParameter('startDateTime', $filters -> startDateTime);
       }
       if(!empty($filters -> deadline))
       {
            $query = $query 
                ->andWhere('s.deadline <= :deadline')
                ->setParameter('deadline', $filters -> deadline);
       }
       if($filters -> tripsOrganized)
       {
            $query = $query 
                ->andWhere('s.creator = :creator')
                ->setParameter('creator', $user);
       }
       if($filters -> tripsRegisted)
       {
            $query = $query 
                ->andWhere(':user MEMBER OF s.users')
                ->setParameter('user', $user);
       }
       if($filters -> tripsNotRegisted)
       {
            $query = $query 
                ->andWhere('s.tripsNotRegisted NOT MEMBER OF s.users')
                ->setParameter('tripsNotRegisted',$user);
       }
       if($filters -> tripsPassed)
       {
            $query = $query 
                ->andWhere('s.status = :status')
                ->setParameter('status','');
       }
       
        return $query ->getQuery() -> getResult();

    }

    //FILTRES LES TRIPS EN FONCTION DE LA DATE
   /* public function dateInterval(Filters $filters)
    {
        $query = $this  -> findTrip($filters)
                        -> andWhere('s.startDateTime >= :startDateTime')
                        -> andWhere('s.deadline <= :deadline')
                        -> getQuery();
        return $query;
    }*/



    public function remove(Trip $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return Out[] Returns an array of Out objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('o')
//            ->andWhere('o.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('o.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Out
//    {
//        return $this->createQueryBuilder('o')
//            ->andWhere('o.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
