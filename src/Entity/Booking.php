<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping\PrePersist;
use App\Repository\BookingRepository;
use Symfony\Component\HttpFoundation\Response;

/**
 * @ORM\Entity(repositoryClass=BookingRepository::class)
 * @ORM\HasLifecycleCallbacks()
 */
class Booking
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="bookings")
     * @ORM\JoinColumn(nullable=false)
     */
    private $booker;

    /**
     * @ORM\ManyToOne(targetEntity=Ad::class, inversedBy="bookings")
     * @ORM\JoinColumn(nullable=false)
     */
    private $ad;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\Date(message="Le format doit être une date")
     * @Assert\GreaterThan("today",message="La date d'arrivée doit être ulterieur à la date d'aujourd'hui",groups="front")
     */
    private $startDate;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\Date(message="Le format doit être une date")
     * @Assert\GreaterThan(propertyPath="startDate",message="La date de départ doit être plus éloigque la date d'arrivée")
     
     */
    private $endDate;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="float")
     */
    private $amount;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $comment;

    /**
     * Callback appelé à chaque fois qu'on crée une réservation
     * @ORM\PrePersist
     * @ORM\PreUpdate
     * @return Response
     */

    public function prePersist()
    {
        if(empty($this->createdAt)){
            $this->createdAt = new \DateTime();
        }

        if(empty($this->amount)){

            //prix de l'annonce * nbre de jour
            $this->amount = $this->ad->getPrice() * $this->getDuration();
        }
    }

    public function isBookabledays()
    {
        //1- il faut connaitre les dates déja reservées
        $notAvailableDays = $this->ad->getNotAvailableDays();
        //2- il faut connaitre les dates en cours de résérvation
        $bookingDays =  $this->getDays();
        //3- comparaison 
        $notAvailableDays = array_map(function($day){
            return$day->format('Y-m-d');
        },$notAvailableDays);

        $days = array_map(function($day){
            return$day->format('Y-m-d');
        },$bookingDays);

         //4- on retourne vrai ou faux
        foreach($days as $day){

            if(array_search($day, $notAvailableDays) !== false) return false;
 
            }
            return true;
 
    }
    public function getDays()
    {
        $resultat = range($this->startDate->getTimestamp(),$this->endDate->getTimestamp(),24*60*60);    
        $days = array_map(function($dayTimestamp){
            return new \DateTime(date('Y-m-d',$dayTimestamp));
        },$resultat);
           return $days; 
    }

    //Calcul du nombre de jours du séjour
    public function getDuration()
    {
        $difference = $this->endDate->diff($this->startDate);
        //retourner la difference de nbre de jours
        return $difference->days;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBooker(): ?User
    {
        return $this->booker;
    }

    public function setBooker(?User $booker): self
    {
        $this->booker = $booker;

        return $this;
    }

    public function getAd(): ?Ad
    {
        return $this->ad;
    }

    public function setAd(?Ad $ad): self
    {
        $this->ad = $ad;

        return $this;
    }

    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->startDate;
    }

    public function setStartDate(\DateTimeInterface $startDate): self
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->endDate;
    }

    public function setEndDate(\DateTimeInterface $endDate): self
    {
        $this->endDate = $endDate;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getAmount(): ?float
    {
        return $this->amount;
    }

    public function setAmount(float $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(?string $comment): self
    {
        $this->comment = $comment;

        return $this;
    }
}
