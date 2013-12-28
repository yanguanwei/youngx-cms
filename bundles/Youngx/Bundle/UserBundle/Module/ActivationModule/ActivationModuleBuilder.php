<?php

namespace Youngx\Bundle\UserBundle\Module\ActivationModule;

use Youngx\DI\ContainerBuilder;
use Youngx\DI\DefinitionCollection;

class ActivationModuleBuilder extends ContainerBuilder
{
    public function collect(DefinitionCollection $collection)
    {
        $collection->register('user.activation.listener.collect', __NAMESPACE__ . '\Listener\CollectListener')
            ->tag('listener');
    }
}