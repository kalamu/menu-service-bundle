<?php

namespace Kalamu\MenuServiceBundle\DependencyInjection;

use Kalamu\MenuServiceBundle\Menu\MenuBuilder;
use Knp\Menu\MenuItem;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class KalamuMenuServiceExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $container->setParameter('kalamu_menu_service.menus', $config);

        foreach ($config as $name => $infos) {
            $definition = new Definition(MenuItem::class, array($name));
            $definition->setFactory([new Reference(MenuBuilder::class), 'createMenu']);
            $definition->addTag('knp_menu.menu', array('alias' => $name));
            $definition->setPublic(true);

            $container->setDefinition('kalamu_menu_service.menu.'.$name, $definition);
        }

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');
    }
}
