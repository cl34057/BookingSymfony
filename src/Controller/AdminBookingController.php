<?php

namespace App\Controller;

use App\Entity\Booking;
use App\Form\AdminBookingType;
use App\Repository\BookingRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminBookingController extends AbstractController
{
    /**
     * Affiche la liste des réservations
     * @Route("/admin/bookings", name="admin_bookings_list")
     * 
     * @return Response
     */
    public function index(BookingRepository $repo)
    {
        return $this->render('admin/booking/index.html.twig', [
            'bookings' => $repo->findAll()
        ]);
    }

    /**
     * Permet d'esiter uen réservation admin'
     * @Route("/admin/booking/{id}/edit", name="admin_booking_edit")
     * @param Booking $booking
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     * 
     */

    public function edit(Booking $booking,Request $request,EntityManagerInterface $manager){

        $form = $this->createForm(AdminBookingType::class,$booking);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){

              //  $booking->setAmount($booking->getAd()->getPrice() * $booking->getDuration())

              $booking->setAmount(0);
              

                $manager->persist($booking);

                $manager->flush();
                $this->addFlash('success',"La réservation a bien été modifiée");
        }

        return $this->render('admin/booking/edit.html.twig',[
            'booking'=>$booking,
            'form'=>$form->createView()

        ]);
    }


     /**
     * Suppression d'une reservation via l'admin
     * @Route("/admin/booking/{id}/delete",name="admin_booking_delete")
     * @param Booking $booking
     * @param EntityMAnagerInterface $manager
     * @return Response
     * 
     */

    public function delete(Booking $booking,EntityManagerInterface $manager){

       
        
        $manager->remove($booking);
        $manager->flush();

        $this->addFlash("success","reservation n° {$booking->getId} a été supprimé !");
        
        return $this->redirectToRoute('admin_bookings_list');

    }
}
