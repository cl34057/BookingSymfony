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
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;



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
     * @route("/ads/new",name="ads_create")
     * @IsGranted("ROLE_USER")
     * @return response
     */
   
    public function create(Request $request,EntityManagerInterface $manager){
            //fabricant de formulaire FORMBUILDER
            $ad= new Ad();
           
            //On lance la fabrication et la configuration de notre formulaire
            $form= $this->createForm(AnnonceType::class,$ad);

            //récupération des données du formulaire (avec sécurité prévue par symfony)
            $form-> handleRequest($request);


             //s'il est valide et il a été soumis
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
                                
                         $ad->setAuthor($this->getUser());
                     
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
     * @security("is_granted('ROLE_USER') and user === ad.getAuthor()",message="Cette annonce ne vous appartient pas, Vous ne pouvez pas modifier")
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
    /**
     * suppression d'une annonce
     * @Route("/ads/{slug}/delete",name="ads_delete")
     * @Security("is_granted('ROLE_USER') and user == ad.gatAuthor()",message="Vous n'avez pas le droit d'accéder à cette ressource")
     * @param Ad $ad
     * @param EntityManagerInterface $entityManager
     * @return void
     */

    public function delete(Ad $ad,EntityManagerInterface $entityManager){
                            $entityManager->remove($ad);

                            $entityManager->flush();
                            $this->addFlash("success","L'annonce  <em>{$ad->getTitle()}</em> a bien été supprimé");

                            return $this->redirectToRoute("ads_list");

                          }

    /**
     * Methode pour ajouter 10 au prix de l'annonce
     * @Route("/ads/{id}/ajax",options={"expose"=true},name="ads_prix")
     *
     * @return response
     */
    public function augment(Ad $ad){

                $ad->setPrice($ad->getPrice() +10);
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($ad);
                $entityManager->flush();

                return new JsonResponse(['success'=>200]);
              }
}

