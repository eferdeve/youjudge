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

    public function avgNote()
    {
        $db = $this->getEntityManager()->getConnection();

        $req = "SELECT *, AVG(note) AS moyenne FROM notes GROUP BY jeu_id";

        $result = $db->prepare($req);
        $result->execute();

        return $result->fetchAll();
    }

    public function targetAvg($id)
    {
        $db = $this->getEntityManager()->getConnection();

        $req = "SELECT *, AVG(note) AS moyenne FROM notes WHERE jeu_id = ?  GROUP BY jeu_id";

        $result = $db->prepare($req);
        $result->execute(array($id));

        return $result->fetch();
    }

    public function noteCount()
    {
        $db = $this->getEntityManager()->getConnection();

        $req = "SELECT COUNT(id) AS total FROM notes";
        $result = $db->prepare($req);

        $result->execute();

        return $result->fetch();
    }

    public function hasVoted($userId, $gameId)
    {
        $db = $this->getEntityManager()->getConnection();

        $req = "SELECT * FROM notes WHERE user_id = $userId AND jeu_id = $gameId";
        $result = $db->prepare($req);

        $result->execute();

        return $result->fetch();
    }

    //Nombre total de note par jeu, à rajouter si le site prend de l'ampleur..
    
    /*public function noteCountQuery()
    {
        $db = $this->getEntityManager()->getConnection();

        $req = "SELECT COUNT(id) AS total FROM notes GROUP BY jeu_id";
        $result = $db->prepare($req);

        $result->execute(array());

        return $result->fetchAll();
    }
    */
    
    

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
