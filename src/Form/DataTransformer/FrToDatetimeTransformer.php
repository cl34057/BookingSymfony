<?php

namespace App\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class FrToDatetimeTransformer implements DataTransformerInterface{

    //transforme les données originelles pour qu'elles puissent s'afficher dans une formulaire
    public function transform($date){

        if($date === null){

            return '';
        }
        //retourne une date en fr
        return$date->format('d/m/Y');


    }

    //c'est l'inverse, elle prend sles données qui arrive du formulaire et la remet dans le format qu'on attend
    public function reverseTransform($datefr){

        //date en fr 21/03/19
        if($datefr === null){

            //on lance une exception
            throw new TransformationFailedException("fournir une date");
        }
    $date = \DateTime::createFromFormat('d/m/Y',$datefr);

    if($date === false){

          //on lance une exception
          throw new TransformationFailedException("le format n'est pas correct");
    }
    return $date;

    }

}