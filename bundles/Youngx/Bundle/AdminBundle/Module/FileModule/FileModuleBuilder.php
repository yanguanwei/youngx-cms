<?php

namespace Youngx\Bundle\AdminBundle\Module\FileModule;

use Youngx\DI\ContainerBuilder;
use Youngx\DI\DefinitionCollection;

class FileModuleBuilder extends ContainerBuilder
{
    public function collect(DefinitionCollection $collection)
    {
        $collection->register('file-listener-collect', __NAMESPACE__ . '\Listener\CollectListener')
            ->tag('listener');

        $collection->register('file-listener-main', __NAMESPACE__ . '\Listener\MainListener')
            ->subscribe('context')
            ->tag('listener');
    }
}