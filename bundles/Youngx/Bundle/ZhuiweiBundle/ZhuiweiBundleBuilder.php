<?php

namespace Youngx\Bundle\ZhuiweiBundle;

use Youngx\DI\ContainerBuilder;
use Youngx\DI\DefinitionCollection;

class ZhuiweiBundleBuilder extends ContainerBuilder
{
    public function collect(DefinitionCollection $collection)
    {
        $collection->register('zhuiwei.listener.main', __NAMESPACE__ . '\Listener\MainListener')
            ->subscribe('context')
            ->tag('listener');

        $collection->register('zhuiwei.listener.collect', __NAMESPACE__ . '\Listener\CollectListener')
            ->tag('listener');
    }
}