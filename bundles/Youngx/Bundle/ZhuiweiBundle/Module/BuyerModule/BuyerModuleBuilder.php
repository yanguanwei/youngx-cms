<?php

namespace Youngx\Bundle\ZhuiweiBundle\Module\BuyerModule;

use Youngx\DI\ContainerBuilder;
use Youngx\DI\DefinitionCollection;

class BuyerModuleBuilder extends ContainerBuilder
{
    public function collect(DefinitionCollection $collection)
    {
        $collection->register('buyer.listener.main', __NAMESPACE__ . '\Listener\MainListener')
            ->subscribe('context')
            ->tag('listener');

        $collection->register('buyer.listener.collect', __NAMESPACE__ . '\Listener\CollectListener')
            ->tag('listener');
    }
}