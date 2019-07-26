<?php

namespace App\Form;

use App\Entity\Rechercher;
use App\Entity\Vehicule;
use App\Repository\TypeVehiculeRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RechercherType extends AbstractType
{
    /**
     * @var
     */
    private $typeVehiculeRepository;
    public function __construct(TypeVehiculeRepository $typeVehiculeRepository)
    {
        $this->typeVehiculeRepository = $typeVehiculeRepository;
    }
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('localite',null,[
                'required' => false,
                'label' => false,
                'attr' =>[
                    'placeholder' => 'localité'
                ]
            ])
            ->add('tonnage',IntegerType::class,[
                'required' => false,
                'label' => false,
                'attr' =>[
                    'placeholder' => 'tonnage minimum'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Rechercher::class,
        ]);
    }
}
