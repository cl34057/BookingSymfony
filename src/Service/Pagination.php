<?php

namespace App\Service;

use Twig\Environment;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class Pagination{

    private $entityClass;
    private $limit=10; //par défaut
    private $currentPage=1;

    private $twig;
    private $route;

    private $manager;

    private $templatePath;
                //charger manager
                public function __construct(EntityManagerInterface $manager, Environment $twig,RequestStack $request,$templatePath){

                    $this->route = $request->getCurrentRequest()->attributes->get('_route');
                    
                    $this->manager = $manager;
                    $this->twig = $twig;

                    $this->templatePath = $templatePath;
                }


                public function display(){
                    //appel le moteur ywig  et on precise quelle template on veut utiliser
                    $this->twig->display($this->templatePath,[
                    
                        //options nécéssaires à l'affichage des données
                        //on a besoin des variables: route / page/ pages

                        'page'=>$this->currentPage,
                        'pages'=>$this->getPages(),
                        'route'=>$this->route
                    ]);
                }

       

                 
                 //A partir de quelle entité travailler
        //1- utiliser la pagination à partir de n'importe quelle entité /On devra préciser l'entité concerné

                    //LE SETTER entityClass

                    public function setEntityClass($entityClass){

                        // ma donnée entity  class = donnée qui va m'être envoyé
                        $this->entityClass=$entityClass;
                            //retourne l'objet
                        return $this;

                    }

                    //LE GETTER entityClass
                    public function getEntityClass($entityClass){

                        return $this->entityClass;

                    }

        //2-Quelle est la limite

                    //LE GETTER limit
                    public function getLimit(){

                        return $this->limit;
                    }

                    //LE SETTER limit
                    public function setLimit($limit){

                        $this->limit=$limit;
                        return $this;
                    }
        //3-sur quelle page on se trouve actuellement

                    //LE GETTER currentPage

                   public function getPage(){

                      return $this->currentPage;


                     }

                    //LE SETTER currentPage

                     public function setPage($page){

                         $this->currentPage = $page;

                         return $this;
  
  
                     }

            //4-On va chercher le nombre de page au total des pages
            public function getData(){

                if(empty($this->entityClass)){

                    throw new \Exception("setEntityClass n'a pas été renseigné dans le controller correspondant");

                }

                //Claculer l'offset
                    // $start = $page * $limit -$limit;
                    $offset = $this->currentPage * $this->limit - $this->limit;

                //demande au repository de trouver les éléments
                //on va chercher le bon repository
                    $repo = $this->manager->getRepository($this->entityClass);

                //on construit notre requête
               
                    $data = $repo->findBy([],[],$this->limit,$offset); 
                    //retourner le data
                    return $data;

               }



               public function getPages(){

                    //définir le repo qu'on va chercher
                    $repo = $this->manager->getRepository($this->entityClass);
                    //total des enregistrements
                     $total= count($repo->findAll());
                     $pages=ceil($total/$this-> limit);

                     return $pages;
               }

               public function getRoute(){

                    $this->route;

               }
               public function setRoute($route){

                $this->route= $route;
                return $this;

           }

               public function getTemplatePath(){
    
              return $this->templatePath;
         }
            public function setTemplatePath($templatePath){
            
                $this->templatePath = $templatePath;
                return $this;
          }
}



?>