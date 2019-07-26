<?php

namespace App\Form;

use App\Entity\ALertSearch;
use App\Entity\Vehicule;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AlertSearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('typeProduit',null,[
                'required' => true
            ])
            ->add('nbreVehicule',\Symfony\Component\Form\Extension\Core\Type\IntegerType::class,[
                'required' => true
            ])
            ->add('debutAt',DateType::class)
            ->add('finishAt',DateType::class)
            ->add('villeSepart')
            ->add('villeArrive')
            ->add('descriptionProduits')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ALertSearch::class,
        ]);
    }

}
