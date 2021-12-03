<?php

namespace App\Repository;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

   
    public function findBestUsers($limit = 4){

        return $this->createQueryBuilder('u')                                                     //paramètre ou va se construire la requête est 'u' sur user
                    ->select('u as user, AVG(c.rating) as avgRatings,COUNT(c) as sumComments')    //Trier nos annonceurs par les notes (alias user, avgRatings et sumComments) 
                    ->join('u.Ads','a')                                                           //jointure recuperer les annonces
                    ->join('a.comments','c')                                                      //jointure recuperer les commentaires
                    ->groupBy('u')                                                                //grouper par utilisateur u
                    ->having('sumComments > 3')                                                   //Annonce superieur à 3
                    ->orderBy('avgRatings','DESC')                                                //ordonner par rapport au notes 'avgRatings' descendant DESC
                    ->setMaxResults($limit)                                                       //On met le max de resultat
                    ->getQuery()                                                                  //chercher la requête
                    ->getResult()                                                                 //chercher le resultat
                    ;

    }
}
