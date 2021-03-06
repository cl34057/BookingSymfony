<?php

namespace App\Form;

use App\Entity\Booking;
use App\Form\BookingType;
use App\Form\ApplicationType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use App\Form\DataTransformer\FrToDatetimeTransformer;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class BookingType extends ApplicationType
{

    private $transformer;
    public function __construct(FrToDatetimeTransformer $transformer){
        $this->transformer = $transformer;
    }


    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder

            ->add('startDate',TextType::class,$this->getConfiguration("Date d'arrivée", "La date de votre arrivée"))
           ->add('endDate',TextType::class,$this->getConfiguration("Date d'arrivée", "La date de votre départ"))
         //    ->add('startDate',DateType::class,$this->getConfiguration("Date d'arrivée", "La date de votre arrivée",['widget'=>"single_text"]))
         //    ->add('endDate',DateType::class,$this->getConfiguration("Date de départ", "La date de votre départ",['widget'=>"single_text"]))

            ->add('comment',TextareaType::class,$this->getConfiguration(false, "Ajouter un commentaire sur votre sejour",['required'=>false]))
        ;

        $builder->get('startDate')->addModelTransformer($this->transformer);
        $builder->get('endDate')->addModelTransformer($this->transformer)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Booking::class,
            'validation_groups'=>['Default','front']
        ]);
    }
}
