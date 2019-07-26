<?php

namespace App\Form;

use App\Entity\Vehicule;
use App\Repository\TypeVehiculeRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VehiculeFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('matricule')
            ->add('tonnage')
            ->add('ageVehicule')
            ->add('model')
            ->add('description')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Vehicule::class,
        ]);
    }
    private function getChoice(TypeVehiculeRepository $repository){
        return $choices = $repository->findAll();
    }
}
