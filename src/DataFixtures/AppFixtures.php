<?php

namespace App\DataFixtures;

use App\Entity\Ad;
use Faker\Factory;
use App\Entity\Role;
use App\Entity\User;
use App\Entity\Image;
use App\Entity\Booking;
use App\Entity\Comment;
use Cocur\Slugify\Slugify;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $encoder;
    public function __construct(UserPasswordEncoderInterface $encoder){
        $this->encoder=$encoder;
    }


    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('FR-fr');
        //GESTION DES ROLES
        $adminRole= new Role();
        $adminRole->setTitle('ROLE_ADMIN');
        $manager->persist($adminRole);

        //Création d'un Utilisateur spécial avec un role admin
        $adminUser = new User();
        $adminUser->setFirstName('Francis')
                    ->setLastName('Ramaroson')
                    ->setEmail('jenifra@free.fr')
                    ->setHash($this->encoder->encodePassword($adminUser,'password'))
                    ->setAvatar('https://randomuser.me/api/portraits/men/55.jpg')
                    ->setIntroduction($faker->sentence())
                    ->setDescription("<p>".join("</p><p>",$faker->paragraphs(5)). "</p>")
                    ->addUserRole($adminRole)
                    ;

                   $manager->persist($adminUser) ;

                    


        $users=[];
        $genres=['male','female'];

        //Utilisateurs
        for($u=1; $u<=5;$u++){

                    $user=new User();
                    $genre = $faker->randomElement($genres);
                    $avatar='https://randomuser.me/api/portraits/';
                    $avatarId = $faker->numberBetween(1,99).'.jpg';
                    //condition ternaire
                    $avatar .=($genre == 'male' ? 'men/' : 'women/'). $avatarId;
                    //générer notre Mot de passe qui est relié à l'utilisateur
                    $hash = $this->encoder->encodePassword($user,'password');

                    //->qui va donner par ex randomuser.me/api/portraits/men/11.jpg
                    
                
                    $description= "<p>".join("</p><p>",$faker->paragraphs(5)). "</p>";
                    $user->setDescription($description)
                        ->setFirstname($faker->firstname)
                        ->setLastname($faker->lastname)
                    
                        ->setEmail($faker->email)
                        ->setIntroduction($faker->sentence())
                        ->setHash($hash)
                
                        
                        ->setAvatar($avatar)
                    
                        ;
                        $manager ->persist($user);
                            //on récupère l'utilisateur créé dans ce tableau
                        $users[]=$user;

                     }
                        //Annonces

                    for($i=1; $i<=30;$i++){
                                        $ad = new Ad();

                                        $title = $faker->sentence();
                                    
                                        $coverImage= $faker->imageUrl(1000,350);
                                        $introduction= $faker->paragraph(2);
                                        $content = "<p>".join("</p><p>",$faker->paragraphs(5)). "</p>";
                                        $user = $users[mt_rand(0,count($users)-1)];


                                        $ad->setTitle($title)
                                            ->setCoverImage($coverImage)
                                            ->setIntroduction( $introduction)
                                            ->setContent($content)
                                            ->setPrice(mt_rand(30,200))
                                            ->setRooms(mt_rand(1,5))
                                            //Associe l'annonce à l'utilsateur
                                            ->setAuthor($user)
                                            ;
                                        //rentre les informations dans la base de données
                                        $manager ->persist($ad);

                                        for($j=1;$j<=mt_rand(2,5);$j++){
                                            
                                                                    //on crée une nouvelle instance de l'entité image
                                                                    $image = new Image();
                                                                    $image->setUrl($faker->imageUrl())
                                                                            ->setCaption($faker->sentence())
                                                                            //Associer l'image à l'entité Ad
                                                                            ->setAd($ad) 
                                                                            ;
                                                                    // on sauvegarde
                                                                    $manager ->persist($image);

                                        }
                                        // Gestion des reservations
                                        for($k=1;$k <= mt_rand(0,5);$k++){
                                                                $booking = new Booking();
                                                                    //définir nos variables
                                                                $createdAt = $faker->dateTimeBetween('-6 months');
                                                                $startDate = $faker->dateTimeBetween('-3 months');
                                                                $duration = mt_rand(3,10);
                                                                $endDate = (clone $startDate)->modify("+ $duration days");
                                                                $amount = $ad->getPrice() * $duration;

                                                                //trouver le booker(la personne qui va réserver)
                                                                $booker = $users[mt_rand(0,count($users)-1)];
                                                                $comment= $faker->paragraph();

                                                                //configuration de la réservation
                                                                $booking->setBooker($booker)
                                                                        ->setAd($ad)
                                                                        ->setStartDate($startDate)
                                                                        ->setEndDate($endDate)
                                                                        ->setCreatedAt($createdAt)
                                                                        ->setAmount($amount)
                                                                        ->setComment($comment)
                                                                        ;

                                                                        //persister

                                                                        $manager->persist($booking);


                                         //Géstion des commentaires
                                         if(mt_rand(0,1)){
                                                                
                                                            $comment = new Comment();
                                    
                                                            $comment->setContent($faker->paragraph())
                                                                    ->setRating(mt_rand(1,5))
                                                                    ->setAuthor($booker)
                                                                    ->setAd($ad)
                                                                    ;
                                                            $manager->persist($comment);
                                                    }
                                        }   

                    
                           }    
             //enregistre 
        $manager->flush();
    }
}
