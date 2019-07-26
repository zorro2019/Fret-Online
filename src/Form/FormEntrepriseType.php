<?php

namespace App\Form;

use App\Entity\Entreprise;
use App\Entity\ZoneIntervention;
use App\Repository\ZoneInterventionRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FormEntrepriseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom')
            ->add('ninea',null,[
            ])
            ->add('imageLogo',FileType::class,[
                'required'=>false
            ])
            ->add('adresse')
            ->add('tel')
            ->add('ville')
            ->add('compagnieAssurance')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Entreprise::class,
        ]);
    }
}
