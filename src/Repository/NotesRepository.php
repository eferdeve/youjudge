<?php

namespace App\Repository;

use App\Entity\Notes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Notes|null find($id, $lockMode = null, $lockVersion = null)
 * @method Notes|null findOneBy(array $criteria, array $orderBy = null)
 * @method Notes[]    findAll()
 * @method Notes[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NotesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Notes::class);
    }

    // /**
    //  * @return Notes[] Returns an array of Notes objects
    //  */
    
    /*public function avgNote()
    {
        return $this->createQueryBuilder('n')
            ->select('avg(n.note) as moyenne, n.jeu')
            ->groupBy('n.jeu')
            ->getQuery()
            ->getResult()
        ;
    }
    */

    public function avgNote()
    {
        $db = $this->getEntityManager()->getConnection();

        $req = "SELECT *, AVG(note) AS moyenne FROM notes GROUP BY jeu_id";

        $result = $db->prepare($req);
        $result->execute();

        return $result->fetchAll();
    }
    

    /*
    public function findOneBySomeField($value): ?Notes
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
