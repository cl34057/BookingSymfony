<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\AccountType;
use App\Entity\PasswordUpdate;
use App\Form\RegistrationType;
use App\Form\PasswordUpdateType;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AccountController extends AbstractController
{
            /**
             * login de l'user
             * @Route("/login", name="account_login")
             * @return Response
             */
            public function login(AuthenticationUtils $utils)
            {

                $error =  $utils->getLastAuthenticationError();
                $username =  $utils->getLastUsername();

                return $this->render('account/login.html.twig',[
                    'hasError'=>$error!==null,
                    'username'=>$username
                ]);
            }

                /**
                 * permet de se deconnecter
                 * @Route("/logout",name="account_logout")
                 * 
                 */

                public function logout()
                {
                    //besoin de rien, tout se passe via le fichier security.yaml
                }

                /**
                 * Permet d'afficher une page pour s'inscrire
                 * @Route("/register", name="account_register")
                 * @return Response
                 */
            // public function register(Request $request, UserPasswordEncoderInterface $encoder,ObjectManager $manager){
            public function register(Request $request, UserPasswordEncoderInterface $encoder,ManagerRegistry $manager)
            {
                    $user = new User();
                    $form = $this->createForm(RegistrationType::class,$user);

                    $form->handleRequest($request);
                    //on vérifie et on persiste les données
                    if($form->isSubmitted() && $form->isValid())
                    {
                            //envoi du mot de passe dans la base de données haché

                            $hash = $encoder->encodePassword($user,$user->getHash());
                            //on modifie le mot de pass avec le setter

                            $user->setHash($hash);
                            //Persiste les données
                            $manager = $manager->getManager();
                            $manager->persist($user);
                            $manager->flush();

                            //Message flash

                            $this->addFlash("success","Votre compte a été créé");
                            //redirection vers le login
                            return $this->redirectToRoute("account_login");

                    }

                    return $this->render("account/register.html.twig",['form'=>$form->createView()
                    ]);


            }
                /**
                 * Modification du profil user
                 * 
                 * @Route("/account/profile",name="account_profile")
                 * @IsGranted("ROLE_USER")
                 * @return Response
                 */

            //Public function profile(Request $request,ObjectManager $manager){
                public function profile(Request $request,ManagerRegistry $manager)
                {
                            //symfony retrouvera l'utilisateur connecté à travers getUser
                        $user = $this->getUser();

                        $form=$this->createForm(AccountType::class,$user);
                        $form->handlerequest($request);

                        if($form->isSubmitted() && $form->isValid())
                        {

                            $manager = $manager->getManager();
                            $manager->persist($user);
                            $manager->flush();
                            //message flash

                            $this->addFlash("success","Les informations de votre profil ont été bien modifiées");
                        }
                        
                            return $this->render('account/profile.html.twig',['form'=>$form->createView()
                        ]);

                }
            /**
             * Permet la modif du mdp
             * @Route("/account/password-update",name="account_password")
             * @IsGranted("ROLE_USER")
             * @return Response
             */
            //public function updatePassword(Request $request,UserPasswordEncoderInterface $encoder,ObjectManager $manager){
                public function updatePassword(Request $request,UserPasswordEncoderInterface $encoder,ManagerRegistry $manager)
                {
                    $passwordUpdate = new PasswordUpdate();
                    //Pour chercher l'utilisateur connecté
                    $user=$this->getUser();

                    $form=$this->createForm(PasswordUpdateType::class,$passwordUpdate);

                    $form->handleRequest($request);

                    if($form->isSubmitted() && $form->isValid())
                    {
                            //vérifier si le mot de passe actuel n'est pas le bon(comparaisoon $oldpadssword dans 'PasswordUpdate.php' et $hash de 'User.php' )
                            if(!password_verify($passwordUpdate->getOldPasssword(),$user->getHash()))
                            {
                                    //message d'erreur
                                    //$this->addFlash("xarning","Mdp incorrect");

                                    $form->get('oldPassword')->addError(new FormError("Le Mdp que vous avez entré n'est pas votre MP actuel"));
                            }else{

                                    //on récupère le nouveau mot de passe
                                    $newPassword = $passwordUpdate->getNewPassword();
                                    //on crypte le nouveau mot de passe

                                    $hash = $encoder->encoderPassword($user,$newpassword());

                                    //on modie le nouveau mdp dans le setter
                                    $user->setHash($hash);
                                    //on enregistre
                                    
                                    $manager = $manager->getManager();
                                    $manager->persist($user);
                                    $manager->flush();

                                    //on ajoute un message
                                    $this->addFlash("succes","Votre nouveau Mdp a bien été enregistré");

                                    //on redirige
                                return $this->redirectToRoute('account_profile');
                                }
                        
                     }
                           
                    return $this->render('account/password.html.twig',['form'=>$form->createView()
                    ]);
                }


                        /**
                         * 
                         * Permet d'afficher la page mon compte
                         * @Route("/account",name="account_home")
                         * @IsGranted("ROLE_USER")
                         * @return Response
                         */
                        public function myAccount(){

                            return $this->render("user/index.html.twig",['user'=>$this->getUser()]);
                        }
                
                        /**
                         * Affiche la liste des réservations de l'utilisateur
                         * @Route("/account/bookings", name="account_bookings")
                         * 
                         * @return Response
                         */


                        public function bookings(){

                            return $this->render('account/bookings.html.twig');
                        }
    }


