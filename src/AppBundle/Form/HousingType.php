<?php

namespace AppBundle\Form;

use AppBundle\Entity\Building;
use AppBundle\Entity\Tenant;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class HousingType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', null, ["label" => "Nom"])
            ->add('rent', null, ["label" => "Loyer"])
            ->add('costs', null, ["label" => "Charges"])
            ->add('building', EntityType::class, [
                "label" => "Immeuble",
                "class" => Building::class,
                "choice_label" => "address.address1"
            ])
            ->add('tenant', EntityType::class, [
                "label" => "Locataire",
                "class" => Tenant::class,
                "placeholder" => "Aucun locataire",
                "required" => false,
                "choice_label" => function (Tenant $tenant) {
                    return strtoupper($tenant->getLastName()) . " " . $tenant->getFirstName();
                }
            ]);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Housing'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_housing';
    }


}
