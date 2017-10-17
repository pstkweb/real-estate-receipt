<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;

class ReceiptsGeneratorType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $years = range(idate('Y') - 9, idate('Y') + 1);

        $builder
            ->add('month', ChoiceType::class, [
                'label' => 'Mois',
                'choices' => [
                    "Janvier" => 1,
                    "Février" => 2,
                    "Mars" => 3,
                    "Avril" => 4,
                    "Mai" => 5,
                    "Juin" => 6,
                    "Juillet" => 7,
                    "Aout" => 8,
                    "Septembre" => 9,
                    "Octobre" => 10,
                    "Novembre" => 11,
                    "Décembre" => 12
                ]
            ])
            ->add('year', ChoiceType::class, [
                'label' => 'Année',
                'choices' => array_combine($years, $years)
            ]);
    }

    public function getBlockPrefix()
    {
        return 'appbundle_receipt_generator';
    }
}