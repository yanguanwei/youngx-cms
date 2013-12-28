<?php

namespace Youngx\Bundle\UserBundle\Module\RoleModule;

use Youngx\DI\ContainerBuilder;
use Youngx\DI\DefinitionCollection;

class RoleModuleBuilder extends ContainerBuilder
{
    public function collect(DefinitionCollection $collection)
    {
        $collection->register('user.role.listener.collect',  __NAMESPACE__ . '\Listener\CollectListener')
            ->tag('listener');

        $collection->register('user.role.listener.main',  __NAMESPACE__ . '\Listener\MainListener')
            ->subscribe('context')
            ->tag('listener');

        $collection->register('user.role.listener.account',  __NAMESPACE__ . '\Listener\AccountListener')
            ->subscribe('context')
            ->tag('listener');


        $collection->register('user.role', __NAMESPACE__ . '\Service\UserRoleService', true)
            ->subscribe('context');
    }
}