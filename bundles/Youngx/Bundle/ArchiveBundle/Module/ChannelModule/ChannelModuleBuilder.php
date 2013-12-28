<?php

namespace Youngx\Bundle\ArchiveBundle\Module\ChannelModule;

use Youngx\DI\ContainerBuilder;
use Youngx\DI\DefinitionCollection;

class ChannelModuleBuilder extends ContainerBuilder
{
    public function collect(DefinitionCollection $collection)
    {
        $collection->register('channel.listener.main', __NAMESPACE__ . '\Listener\MainListener')
            ->subscribe('context')
            ->tag('listener');

        $collection->register('channel.listener.collect', __NAMESPACE__ . '\Listener\CollectListener')
            ->tag('listener');
    }
}