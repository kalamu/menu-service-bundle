<?php

namespace Kalamu\MenuServiceBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Custom Compiler
 */
class CustomPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        
        $definition = $container->findDefinition('kalamu_menu_service.menu_builder');
        if($container->has('security.authorization_checker')){
            $definition->addMethodCall('setSecurity', array(new Reference('security.authorization_checker')));
        }else{ // <= Symfony 2.5
            $definition->addMethodCall('setSecurity', array(new Reference('security.context')));
        }
        
    }
}
