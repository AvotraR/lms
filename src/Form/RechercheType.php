<?php

namespace App\Form;

use App\Entity\Categorie;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class RechercheType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Categorie',EntityType::class,[
                'class' => Categorie::class,
                'choice_label' => 'NomCat'
            ])
            ->add('PrixMin',IntegerType::class,[
                'label' => False,
                'required' => false,
                'attr' => ['placeholder'=>'Prix Minimum...']
            ])
            ->add('PrixMax',IntegerType::class,[
                'label' => False,
                'required' => false,
                'attr' => ['placeholder'=>'Prix Maximum...']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
