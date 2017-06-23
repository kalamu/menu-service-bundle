<?php

namespace Kalamu\MenuServiceBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('kalamu_menu_service');

        $rootNode
                ->useAttributeAsKey('id')
                ->prototype('array')
                    ->fixXmlConfig('menu')
                    ->children()
                        ->scalarNode('class')->end()
                        ->append($this->addItems(3))
                    ->end()
                ->end();

        return $treeBuilder;
    }


    protected function addItems($nb_nested = 0){

        $builder = new TreeBuilder();
        $node = $builder->root('items');

        $suite = $node
            ->prototype('array')
                ->addDefaultsIfNotSet()
                ->children()
                    ->scalarNode('label')->isRequired()->end()
                    ->scalarNode('route')->end()
                    ->scalarNode('class')->end()
                    ->arrayNode('roles')->prototype('scalar')->end()->end()
                    ->scalarNode('allow_if')->end()
                    ->booleanNode('hide_if_no_child')->defaultFalse()->end();

        if($nb_nested>0){ // FIXME : if we go deeper, the memory consumption is too high
            $suite = $suite
                    ->scalarNode('icon')->defaultValue('<i class="fa fa-folder"></i>')->end()
                    ->append($this->addItems($nb_nested-1));
        }else{
            $suite = $suite
                    ->scalarNode('icon')->defaultValue('<i class="fa fa-angle-double-right"></i>')->end();
        }

        $suite
                ->end()
            ->end();

        return $node;


    }

}
