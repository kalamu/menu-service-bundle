<?php

namespace Kalamu\MenuServiceBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Kalamu\MenuServiceBundle\DependencyInjection\Compiler\CustomPass;

class KalamuMenuServiceBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);
        
        $container->addCompilerPass(new CustomPass());
    }
}
