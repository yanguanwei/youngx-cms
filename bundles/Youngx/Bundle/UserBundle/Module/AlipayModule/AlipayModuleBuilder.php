<?php

namespace Youngx\Bundle\UserBundle\Module\AlipayModule;

use Youngx\Kernel\Container\ContainerBuilder;
use Youngx\Kernel\Container\DefinitionCollection;

class AlipayModuleBuilder extends ContainerBuilder
{
    public function collect(DefinitionCollection $collection)
    {
        $collection->register('user.alipay.listener.collect', __NAMESPACE__ . '\Listener\CollectListener')
            ->tag('listener');
    }
}