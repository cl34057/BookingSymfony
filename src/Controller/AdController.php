<?php

namespace App\Controller;

use App\Entity\Ad;
use App\Entity\Image;
use App\Form\AnnonceType;
use App\Repository\AdRepository;
use Doctrine\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdController extends AbstractController
{
    /**
     * permet d'afficher une liste d'annonces
     * @Route("/ads", name="ads_list")
     */
    public function index(AdRepository $repo)
    {

       // $repo = $this->getDoctrine()->getRepository(Ad::class);
        //via $repo, on va aller chercher toutes les annonces via la methode findAll

        $ads = $repo->findAll();

        return $this->render('ad/index.html.twig', [
            'controller_name' => 'Nos annonces',
            'ads'=>$ads
        ]);
    }
     /**
     * Permet de creer une annonce
     *@route("/ads/new",name="ads_create")
     * @return response
     */
   // public function create(Request $request,ObjectManager $manager){
    public function create(Request $request,ManagerRegistry $manager){
            //fabricant de formulaire FORMBUILDER
            $ad= new Ad();
           
            //On lance la fabrication et la configuration de notre formulaire
            $form= $this->createForm(AnnonceType::class,$ad);

            //récupération des données du formulaire (avec sécurité prévue par symfony)
            $form-> handleRequest($request);


             
                if($form->isSubmitted() && $form->isValid()){
                        //si le formulaire est soumisET si le formulaire est valide, on demande à Doctrine de sauvegarder
                        //ces données dans l'objet $manager
                    

                        // pour chaque image supplémentaire ajoutée
                        foreach($ad->getImages() as $image){

                                        //on relie l'image à l'annonce et on modifie l'annonce

                                            $image -> setAd($ad);
                                        
                                        //on sauvegarde les images

                                        $manager->persist($image);

                                 }

                        $manager = $manager->getManager();
                        $manager ->persist($ad);
                        $manager->flush();

                        //message Flash
                        $this->addFlash('success',"Annonce <strong>{$ad->getTitle()}</strong> créée avec succès");

                        return $this->redirectToRoute('ads_single',['slug'=>$ad->getSlug()]);

                }
                   
        return $this->render('ad/new.html.twig',['form'=>$form->createView()]);

    }

    /**
     * permet d'afficher uneseule annonce
     * @Route("/ads/{slug}", name="ads_single")
     *
     * @return Response
     */
    public function show($slug,Ad $ad){
        //je recupère l'annonce qui correspond au slug
        //X= 1 champ de la table à préciser à la place de X (slug,id....)
        //findByX = renvoi un tableau d'annonces(+eurs elements)
        //findOneByX = renvoi un element
        //$ad = $repo->findOneBySlug($slug);

        return $this->render('ad/show.html.twig',['ad'=>$ad]);

    }
    /**
     * Permet d'editer et modifier un article
     * @Route("/ads/{slug}/edit", name="ads_edit")
     * 
     */
   
    //public function edit(Ad $ad,Request $request,ManagerRegistry $manager){
        public function edit(Ad $ad,Request $request,EntityManagerInterface $entityManager){

        $form= $this->createForm(AnnonceType::class,$ad);
        $form->handleRequest($request);


        

       if($form->isSubmitted() && $form->isValid()){
                        //si le formulaire est soumisET si le formulaire est valide, on demande à Doctrine de sauvegarder
                        //ces données dans l'objet $manager
                    

                        // pour chaque image supplémentaire ajoutée
                        foreach($ad->getImages() as $image){

                                        //on relie l'image à l'annonce et on modifie l'annonce

                                            $image -> setAd($ad);
                                        
                                        //on sauvegarde les images

                                        $entityManager->persist($image);

                                 }

                        //manager = $manager->getManager();
                        // $manager ->persist($ad);
                       // $manager->flush();
                       $entityManager = $this->getDoctrine()->getManager();
                       $entityManager->flush();

                        //message Flash
                        $this->addFlash("success","Modifications éffectuées!");

                        return $this->redirectToRoute('ads_single',['slug'=>$ad->getSlug()]);

                }
                   
        return $this->render('ad/edit.html.twig',['form'=>$form->createView(),'ad'=>$ad]);
    }
}
