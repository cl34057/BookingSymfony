<?php

namespace App\Form;

use App\Entity\User;
use App\Form\ApplicationType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

//class RegistrationType extends AbstractType
class RegistrationType extends ApplicationType
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
            ->add('firstname',TextType::class,$this->getConfiguration("Nom","votre nom..."))
            ->add('lastname',TextType::class,$this->getConfiguration("Nom","votre prénom..."))
            ->add('email',EmailType::class,$this->getConfiguration("Email","Un email valide"))
            ->add('hash',PasswordType::class,$this->getConfiguration("Mot de passe","Choisissez un bon mot de passe"))
            ->add('passwordConfirm',PasswordType::class,$this->getConfiguration("Confirmez le mdp","Confirmer le mot de passe"))
            ->add('introduction',TextType::class,$this->getConfiguration("Introduction","Déscription courte pour se présenter"))
            ->add('description',TextareaType::class,$this->getConfiguration("description","Déscription détaillée"))
            ->add('avatar',UrlType::class,$this->getConfiguration("Avatar","Url de votre avatar"))
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
