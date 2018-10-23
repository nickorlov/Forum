<?php

namespace ForumBundle\Menu;

use Knp\Menu\FactoryInterface;

class MenuBuilder
{
    /** @var FactoryInterface */
    private $factory;

    /**
     * @param FactoryInterface $factory
     */
    public function __construct(FactoryInterface $factory)
    {
        $this->factory = $factory;
    }

    public function createMainMenu(array $options)
    {
        $menu = $this->factory->createItem('root');
        $menu->setChildrenAttribute('class', 'nav navbar-nav');

        $menu->addChild('Home', ['route' => 'homepage']);
        $menu->addChild('Login', ['route' => 'fos_user_security_login']);
        $menu->addChild('Register', ['route' => 'fos_user_registration_register']);

        return $menu;
    }
}