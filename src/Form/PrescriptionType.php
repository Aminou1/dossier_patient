<?php

namespace App\Form;

use App\Entity\Prescription;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;


class PrescriptionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('libellePrescrip',TextType::class)
            ->add('dossiers', EntityType::class, array(
                'class' => 'App\Entity\Dossier',
                'choice_label' => 'code'))
            ->add('prescriptiontype', EntityType::class, array(
                'class' => 'App\Entity\TypePrescription',
                'choice_label' => 'libelletypeprescription'))
            ->add('structures', EntityType::class, array(
                'class' => 'App\Entity\Structure',
                'choice_label' => 'nomStructure'))
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Prescription::class,
        ]);
    }
}
