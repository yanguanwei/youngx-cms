<?php

namespace Youngx\Bundle\ZhuiweiBundle\Module\SellerModule;

use Youngx\DI\ContainerBuilder;
use Youngx\DI\DefinitionCollection;

class SellerModuleBuilder extends ContainerBuilder
{
    public function collect(DefinitionCollection $collection)
    {
        $collection->register('seller.listener.main', __NAMESPACE__.'\Listener\MainListener')
            ->subscribe('context')
            ->tag('listener');

        $collection->register('seller.listener.collect', __NAMESPACE__.'\Listener\CollectListener')
            ->tag('listener');
    }
}