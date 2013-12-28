<?php

namespace Youngx\Bundle\KernelBundle\Module\CKFinderModule;

use Youngx\DI\ContainerBuilder;
use Youngx\DI\DefinitionCollection;

class CKFinderModuleBuilder extends ContainerBuilder
{
    public function collect(DefinitionCollection $collection)
    {
        $collection->register('ckfinder.listener.view', __NAMESPACE__ . '\Listener\ViewListener')
            ->tag('listener')
            ->subscribe('context');
    }
}