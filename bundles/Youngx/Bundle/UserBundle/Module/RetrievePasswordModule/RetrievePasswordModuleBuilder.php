<?php

namespace Youngx\Bundle\UserBundle\Module\RetrievePasswordModule;

use Youngx\Kernel\Container\ContainerBuilder;
use Youngx\Kernel\Container\DefinitionCollection;

class RetrievePasswordModuleBuilder extends ContainerBuilder
{
    public function collect(DefinitionCollection $collection)
    {
        $collection->register('user.retrieve_password.listener.collect', __NAMESPACE__ . '\Listener\CollectListener')
            ->tag('listener');
    }
}