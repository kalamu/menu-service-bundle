<?php

namespace Kalamu\MenuServiceBundle\Menu;

use Knp\Menu\FactoryInterface;
use Kalamu\MenuServiceBundle\Event\ConfigureMenuEvent;
use Knp\Menu\MenuItem;

/**
 * This service generate the knp menus
 */
class MenuBuilder
{

    protected $config;
    protected $factory;
    protected $security;
    protected $event_dispatcher;

    public function __construct($config, FactoryInterface $factory, $event_dispatcher){
        $this->config = $config;
        $this->factory = $factory;
        $this->event_dispatcher = $event_dispatcher;
    }
    
    public function setSecurity($security){
        $this->security = $security;
    }

    public function createMenu($name){

        if(!array_key_exists($name, $this->config)){
            throw new \InvalidArgumentException(sprintf("There is no menu named '%s'", $name));
        }

        $menu = $this->factory->createItem('root');

        if(isset($this->config[$name]['class'])){
            $menu->setChildrenAttribute('class', $this->config[$name]['class']);
        }

        foreach($this->config[$name]['items'] as $item){
            $this->addItem($menu, $item);
        }

        $this->event_dispatcher->dispatch('kalamu.menu_service.configure.'.$name, new ConfigureMenuEvent($this->factory, $menu));

        return $menu;
    }


    protected function addItem(MenuItem $root, $config){
        foreach($config['roles'] as $role){
            if(!$this->security->isGranted($role)){
                return null;
            }
        }

        $name = $config['label'];
        $label = (isset($config['icon']) ? $config['icon'].' ' : ' ');
        $label .= "<span class='sidebar-title'>".$config['label']."</span>";
        $label .= (isset($config['items']) && count($config['items'])) ? '<span class="caret"></span>' : '';

        $options = array('label' => $label, 'extras' => array('safe_label' => true));
        if('#' == $config['route']){
            $options['uri'] = '#';
        }elseif($config['route']){
            $options['route'] = $config['route'];
        }


        if($root->getChild($name)){
            $name .= microtime();
        }
        $MenuItem = $root->addChild($name, $options);
        if(isset($config['class'])){
            $MenuItem->setAttribute('class', $config['class']);
        }


        if(isset($config['items'])){
            foreach($config['items'] as $item){
                $this->addItem($MenuItem, $item);
            }

            if($root->getLevel()){
                $root->setChildrenAttribute('class', 'nav sub-nav');
                $root->setLinkAttribute('class', 'accordion-toggle');
            }

        }

    }

}