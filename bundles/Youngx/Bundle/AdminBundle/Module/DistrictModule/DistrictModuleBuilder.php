<?php

namespace Youngx\Bundle\AdminBundle\Module\DistrictModule;

use Youngx\DI\ContainerBuilder;
use Youngx\DI\DefinitionCollection;

class DistrictModuleBuilder extends ContainerBuilder
{
    public function collect(DefinitionCollection $collection)
    {
        $collection->register('district.list.main', __NAMESPACE__ . '\Listener\MainListener')
            ->subscribe('context')
            ->tag('listener');

        $collection->register('district.list.collect', __NAMESPACE__ . '\Listener\CollectListener')
            ->tag('listener');
    }
}