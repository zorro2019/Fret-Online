<?php

namespace App\Form;

use App\Entity\Abonnes;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FormAbonneType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom')
            ->add('prenom')
            ->add('telephone',TelType::class,[
                'help'=> 'exple: 777777777'
            ])
            ->add('email',EmailType::class)
            ->add('imageProfil',FileType::class, [
                'required'=>false,
                'label' => 'Image jpec',
                'attr' => ['placeholder' => 'Choose file'],
            ])
            ->add('password',PasswordType::class)
            ->add('pwd',PasswordType::class)
            ->add('typeAbonne',ChoiceType::class,[
                'choices' => $this->getChoice(),
                'label'=>'Type'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Abonnes::class,
            'translation_domain' => 'forms'
        ]);
    }
    private function getChoice(){
        $choices = Abonnes::TYPEAB;
        $output = [];
        foreach ($choices as $value => $item){
            $output[$item] = $value;
        }
        return $output;
    }
}
