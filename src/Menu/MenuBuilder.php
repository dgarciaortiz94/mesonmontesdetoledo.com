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
}