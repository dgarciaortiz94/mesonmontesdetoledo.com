<?php
// src/Menu/MenuBuilder.php

namespace App\Menu;

use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;
use Knp\Menu\Matcher\Matcher;
use Knp\Menu\Matcher\Voter\UriVoter;
use Knp\Menu\Renderer\ListRenderer;

class MenuBuilder
{
    private $factory;

    /**
     * Add any other dependency you need...
     */
    public function __construct(FactoryInterface $factory)
    {
        $this->factory = $factory;
    }

    public function createAdminMainMenu(array $options): ItemInterface
    {
        $menu = $this->factory->createItem('root');

        $menu->addChild(
            'Home', 
            [
                'route' => 'app_admin-panel',
                'extras' => [
                    'icon' => 'fas fa-home',
                ],
            ],
            
        );

        $menu->addChild(
            'Administradores', 
            [
                'route' => 'app_user_index',
                'extras' => [
                    'icon' => 'fas fa-user-cog',
                ],
            ],
        );

        return $menu;
    }


    public function createWebsiteMainMenu(array $options): ItemInterface
    {
        $menu = $this->factory->createItem('root');

        $menu->addChild('El Mesón', ['route' => 'app_website_home']);
        $menu->addChild('Nuestra carta', ['route' => 'app_website_menu']);
        $menu->addChild('Terraza de verano', ['route' => 'app_website_menu']);
        $menu->addChild('Horario', ['route' => 'app_website_menu']);
        $menu->addChild('Dónde estamos', ['route' => 'app_website_menu']);

        return $menu;
    }
}