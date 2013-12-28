<?php

namespace Youngx\Bundle\UserBundle;

use Youngx\DI\ContainerBuilder;
use Youngx\DI\DefinitionCollection;
use Youngx\DI\DefinitionProcessor;

class UserBundleBuilder extends ContainerBuilder
{
    public function collect(DefinitionCollection $collection)
    {
        $collection->register('user.listener.main', __NAMESPACE__ . '\Listener\MainListener')
            ->subscribe('context')
            ->tag('listener');

        $collection->register('user.listener.collect', __NAMESPACE__ . '\Listener\CollectListener')
            ->tag('listener');

        $collection->register('user.listener.exception', __NAMESPACE__ . '\Listener\ExceptionListener')
            ->subscribe('context')
            ->tag('listener');
    }

    public function process(DefinitionProcessor $processor)
    {
        $processor->getDefinition('user.identity.storage')
            ->subscribe('context')
            ->setClass(__NAMESPACE__.'\IdentityStorage');
    }
}