<?php

namespace App\Repository;

use App\Entity\Commentaires;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Commentaires|null find($id, $lockMode = null, $lockVersion = null)
 * @method Commentaires|null findOneBy(array $criteria, array $orderBy = null)
 * @method Commentaires[]    findAll()
 * @method Commentaires[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommentairesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Commentaires::class);
    }

    public function authorComment($commentid)
    {
        $db = $this->getEntityManager()->getConnection();

        $req = "SELECT contenu, created_at, users.pseudo as pseudo FROM commentaires LEFT JOIN users ON commentaires.auteur_id = users.id WHERE commentaires.id = '$commentid'";
        $result = $db->prepare($req);

        $result->execute();

        return $result->fetch();
    }

    
    public function hasCommented($userId, $gameId)
    {
        $db = $this->getEntityManager()->getConnection();

        $req = "SELECT * FROM commentaires WHERE auteur_id = $userId AND jeu_id = $gameId";
        $result = $db->prepare($req);

        $result->execute();

        return $result->fetch();
    }
    

    
    // /**
    //  * @return Commentaires[] Returns an array of Commentaires objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Commentaires
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
