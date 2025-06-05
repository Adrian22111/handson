<?php

namespace App\Repository;

use App\Entity\MicroPost;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use PhpParser\ErrorHandler\Collecting;

/**
 * @extends ServiceEntityRepository<MicroPost>
 */
class MicroPostRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MicroPost::class);
    }

    //    /**
    //     * @return MicroPost[] Returns an array of MicroPost objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('m')
    //            ->andWhere('m.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('m.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?MicroPost
    //    {
    //        return $this->createQueryBuilder('m')
    //            ->andWhere('m.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

    public function findAllWithComments(): array
    {
        return $this->findAllQuery(
            withComments: true
        )->getQuery()->getResult();
        
        // return $this->createQueryBuilder('p')
        //     ->addSelect('c')
        //     ->leftJoin('p.comments', 'c')
        //     ->orderBy('p.created', 'DESC')
        //     ->getQuery()
        //     ->getResult();
    }

    public function findAllByAuthor(
        int | User $author
    ): array
    {
        return $this->findAllQuery(
            withComments: true,
            withLikes: true,
            withAuthors: true,
            withProfiles: true,
        )->where('p.author = :author')
        ->setParameter(
            'author',
            $author instanceof User ? $author->getId() : $author
        )->getQuery()->getResult();
    }

    private function findAllQuery(
        bool $withComments = false,
        bool $withLikes = false,
        bool $withAuthors = false,
        bool $withProfiles = false,
    ) : QueryBuilder 
    {
        $query = $this->createQueryBuilder('p');
        if($withComments == true){
            $query->leftJoin('p.comments', 'c')
            ->addSelect('c');
        }
        if($withLikes == true){
            $query->leftJoin('p.likedBy', 'l')
            ->addSelect('l');
        }
        if($withAuthors == true || $withProfiles == true){
            $query->leftJoin('p.author', 'a')
            ->addSelect('a');
        }
        if($withProfiles == true){
            $query->leftJoin('a.userProfile', 'up')
            ->addSelect('up');
        }
        
        return $query->orderBy('p.created', 'DESC');
    }

    public function findAllWithMinLikes(int $minLikes): array
    {
        $idList = $this->findAllQuery(
            withLikes: true
        )->select('p.id')
            ->groupBy('p.id')
            ->having('COUNT(l) >= :minLikes')
            ->setParameter('minLikes', $minLikes)
            ->getQuery()->getResult(Query::HYDRATE_SCALAR_COLUMN);

        return $this->findAllQuery(            
            withComments: true,
            withLikes: true,
            withAuthors: true,
            withProfiles: true,
        )->where('p.id in (:idList)')
        ->setParameter('idList', $idList)
        ->getQuery()
        ->getResult();
    }

    public function findAllByAuthors(
        Collection|array $authors
    ): array
    {
        return $this->findAllQuery(
            withComments: true,
            withLikes: true,
            withAuthors: true,
            withProfiles: true,
        )->where('p.author IN (:authors)')
        ->setParameter(
            'authors',
            $authors
        )->getQuery()->getResult();
    }
}
