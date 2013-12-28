<?php

namespace Youngx\Bundle\ZhuiweiBundle\Module\ProductModule;

use Youngx\DI\ContainerBuilder;
use Youngx\DI\DefinitionCollection;

class ProductModuleBuilder extends ContainerBuilder
{
    public function collect(DefinitionCollection $collection)
    {
        $collection->register('zw.product.listener.index', __NAMESPACE__.'\Listener\ProductIndexListener')
            ->subscribe('context')
            ->tag('listener');

        $collection->register('zw.product.listener.main', __NAMESPACE__.'\Listener\MainListener')
            ->subscribe('context')
            ->tag('listener');

        $collection->register('zw.product.listener.collect', __NAMESPACE__.'\Listener\CollectListener')
            ->tag('listener');
    }
}