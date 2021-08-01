<?php
namespace App\Controller;

 
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController{

          /**
         * premiere route
         * @Route("/",name="homepage")
         * 
         */
        public function home(){

            //return new Response("Salut Symfony! C'est ta première page");
            $prenoms = ['Francis1'=>'visiteur','francis2'=>'admin','francis3'=>'contributeur'];
            return $this->render('home.html.twig',['title'=>'Site d\'Annonces','acces'=>'admin','tableau'=>$prenoms]);
    }
    
     /**
         * route hello
         * @Route("/profil/{nom}",name="hello-utilisateur")
         * @Route("/profil/",name="hello-base")
         * @Route("/profil/{nom}/acces/{acces}",name="hello-profil")
         * @return void
         */
        public function hello($nom="Hello R.",$acces="visiteur"){

        //return new Response("Salut Symfony!  Hello " .  $nom. ".Vous avez un accès " .$acces);
        return $this->render('hello.html.twig',['title'=>'Page de Profil','nom'=>$nom,'acces'=>$acces]);
}
}