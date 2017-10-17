<?php

namespace AppBundle\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

class Builder implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    public function mainMenu(FactoryInterface $factory)
    {
        $menu = $factory->createItem('root');
        $menu->setChildrenAttributes(['class' => 'sidebar-menu', 'data-widget' => 'tree']);

        $menu->addChild('Tableau de bord', ['route' => 'app_default_home'])->setExtra('icon', 'dashboard');

        $menu->addChild('Sociétés')->setExtra('header', true);
        $menu->addChild('Liste des sociétés', ['route' => 'company_index'])->setExtra('icon', 'vcard');
        $menu->addChild('Ajouter une société', ['route' => 'company_new'])->setExtra('icon', 'plus');

        $menu->addChild('Biens')->setExtra('header', true);
        $menu->addChild('Liste des biens', ['route' => 'building_index'])->setExtra('icon', 'building');
        $menu->addChild('Ajouter un bien', ['route' => 'building_new'])->setExtra('icon', 'plus');

        $menu->addChild('Logements')->setExtra('header', true);
        $menu->addChild('Liste des logements', ['route' => 'housing_index'])->setExtra('icon', 'home');
        $menu->addChild('Ajouter un logement', ['route' => 'housing_new'])->setExtra('icon', 'plus');

        $menu->addChild('Locataires')->setExtra('header', true);
        $menu->addChild('Liste des locataires', ['route' => 'tenant_index'])->setExtra('icon', 'user');
        $menu->addChild('Ajouter un locataire', ['route' => 'tenant_new'])->setExtra('icon', 'plus');

        return $menu;
    }
}