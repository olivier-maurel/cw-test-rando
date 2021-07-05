<?php

namespace App\Form;

use App\Entity\Hiking;
use App\Entity\HikingDifficulty;
use App\Entity\HikingType as HikingTypes;
// use App\Repository\HikingTypeRepository;

use Symfony\Component\Form\AbstractType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class HikingType extends AbstractType
{
    // private $hikingTypeRepository;

    // public function __construct(HikingTypeRepository $hikingTypeRepository)
    // {
    //     $this->hikingTypeRepository = $hikingTypeRepository;
    // }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email')
            ->add('title')
            ->add('duration')
            ->add('distance')
            ->add('elevation_gain')
            ->add('highest_point')
            ->add('lowest_point')
            ->add('return_start_point')
            ->add('region')
            ->add('municipality')
            ->add('description')
            ->add('picture', FileType::class,[
                'data_class' => null,
                'required' => false,
            ])
            ->add('difficulty', EntityType::class,[
                'class' => HikingDifficulty::class,
                'label' => 'Difficulté',
                'placeholder' => 'Sélectionner une difficulté',
                'mapped' => true,
                'required' => true,
            ])
            ->add('type', EntityType::class,[
                'class' => HikingTypes::class,
                'label' => 'Type',
                'placeholder' => 'Sélectionner une type',
                'mapped' => true,
                'required' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Hiking::class,
        ]);
    }
}
