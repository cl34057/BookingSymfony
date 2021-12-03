<?php
namespace App\Controller;

 
use App\Repository\AdRepository;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController{

          /**
         * premiere route
         * @Route("/",name="homepage")
         * 
         */
        public function home(AdRepository $adRepo, UserRepository $userRepo){

            //return new Response("Salut Symfony! C'est ta première page");
           
            return $this->render('home.html.twig',
                                ['ads'=>$adRepo->findBestAds(6),
                                 'users'=>$userRepo->findBestUsers(2)

                                ]);
    }
    
     /**
         * route hello
         * @Route("/profil/{nom}",name="hello-utilisateur")
         * @Route("/profil/",name="hello-base")
         * @Route("/profil/{nom}/acces/{acces}",name="hello-profil")
         * @return void
         */
        public function hello($nom="anonyme",$acces="visiteur"){

        //return new Response("Salut Symfony!  Hello " .  $nom. ".Vous avez un accès " .$acces);
        return $this->render('hello.html.twig',['title'=>'Page de Profil','nom'=>$nom,'acces'=>$acces]);
    }
}