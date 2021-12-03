<?php
namespace App\Service;

use Doctrine\Persistence\ObjectManager;


class Statistics{
    private $manager;
    public function __construct(ObjectManager $manager){
    $this->manager = $manager;
    }

    public function getStatistics(){
                    $users = $this->getUsersCount();
                    $ads = $this->getAdsCount();
                    $bookings= $this->getBookingsCount();
                    $comments = $this->getCommentsCount();

        return compact('users','ads','bookings','comments');
    }

    public function getUsersCount(){

        return $this->manager->createQuery('SELECT COUNT(u) FROM App\Entity\User u')->getSingleScalarResult();
    }

    public function getAdsCount(){
        return $this->manager->createQuery('SELECT COUNT(a) FROM App\Entity\Ad a')->getSingleScalarResult();

    }
    public function getBookingsCount(){
        return $this->manager->createQuery('SELECT COUNT(b) FROM App\Entity\Booking b')->getSingleScalarResult();

    }
    public function getCommentsCount(){
        return $this->manager->createQuery('SELECT COUNT(c) FROM App\Entity\Comment c')->getSingleScalarResult();

    }

    public function getAdsStats($direction){

        return $this->manager->createQuery
        
        ('SELECT AVG(c.rating) as note,
                a.title,
                a.id,
                u.firstname,
                u.lastname,
                u.avatar
            FROM App\Entity\Comment c
            JOIN c.ad a
            JOIN a.author u
            GROUP BY a
            ORDER BY note '.$direction)

                //on va chercher les 5 meilleurs
            ->setMaxResults(5)
            ->getResult();
       

    }
}