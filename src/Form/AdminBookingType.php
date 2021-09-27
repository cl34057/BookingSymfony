<?php

namespace App\Form;

use App\Entity\Ad;
use App\Entity\User;
use App\Entity\Booking;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class AdminBookingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('startDate',DateType::class,['widget'=>'single_text','label'=>'Début du séjour'])
            ->add('endDate',DateType::class,['widget'=>'single_text','label'=>'Fin de du séjour'])
            ->add('comment',Textarea::class,['label'=>'Commentaire client'])
            ->add('booker',EntityType::class,[
                    'class'=>User::class,
                    'choice_label'=>function($user){
                        return $user->getFirstname(). "  " . strtoupper($user->getlastname());
                    },
                    'label'=>'Visiteur'

            ])
            ->add('ad',EntityType::class,[
                'class'=>Ad::class,
                'choice_label'=>function($ad){
                    return $ad->getId()." - " .$ad->getTitle();

                },
                'label'=>'annonce'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Booking::class,
            'validation_groups'=>['Default']
        ]);
    }

}
