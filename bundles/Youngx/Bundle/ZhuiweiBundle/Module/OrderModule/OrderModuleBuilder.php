<?php

namespace Youngx\Bundle\ZhuiweiBundle\Module\OrderModule;

use Youngx\DI\ContainerBuilder;
use Youngx\DI\DefinitionCollection;

class OrderModuleBuilder extends ContainerBuilder
{
    public function collect(DefinitionCollection $collection)
    {
        $collection->register('zw.order.listener.main', __NAMESPACE__.'\Listener\MainListener')
            ->subscribe('context')
            ->tag('listener');

        $collection->register('zw.order.listener.collect', __NAMESPACE__.'\Listener\CollectListener')
            ->tag('listener');
    }
}