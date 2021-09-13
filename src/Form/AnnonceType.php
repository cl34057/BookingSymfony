<?php

namespace App\Form;

use App\Entity\Ad;
use App\Form\ImageType;
use App\Form\ApplicationType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

//class AnnonceType extends AbstractType
class AnnonceType extends ApplicationType
{
    /**
     * Permet d'avoir la configuration de base d'un champ
     *
     * @param string $label
     * @param string $placeholder
     *  @param string $options
     * @return array
     */
     /*Effacer pour factorisation dans ApplicationType.php
    private function getConfiguration($label,$placeholder,$options=[]){
        return array_merge([
                            'label'=>$label,
                            'attr'=>['placeholder'=>$placeholder]
                            ],
                            $options);
    }*/

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            //->add('title',TextType::class,['label'=>'Titre','attr'=>['placeholder'=>'Titre de l\'annonce']])
            ->add('title',TextType::class,$this->getConfiguration('Titre','insérer un titre'))
            ->add('slug',TextType::class,$this->getConfiguration('Alias','Personalisezun Alias pour générer l\'url',['required'=>false]))
            ->add('coverImage',UrlType::class,$this->getConfiguration('Image de Couverture','Inserez une image'))
            ->add('introduction',TextType::class,$this->getConfiguration('Résumé','Présentez votre bien'))
            ->add('content',TextareaType::class,$this->getConfiguration('Déscription détaillée','Décrivez vos services'))
            ->add('rooms',IntegerType::class,$this->getConfiguration('Nombre de chambres','Nombre de chambres'))
            ->add('price',MoneyType::class,$this->getConfiguration('Prix','Prix des chambres /nuit'))
            ->add('images',CollectionType::class,['entry_type'=>ImageType::class,'allow_add'=>true,'allow_delete'=>true])
         
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Ad::class,
        ]);
    }
}
