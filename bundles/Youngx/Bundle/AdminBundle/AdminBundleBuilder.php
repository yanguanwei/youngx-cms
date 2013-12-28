<?php

namespace Youngx\Bundle\AdminBundle;

use Youngx\DI\ContainerBuilder;
use Youngx\DI\DefinitionCollection;

class AdminBundleBuilder extends ContainerBuilder
{
    public function collect(DefinitionCollection $collection)
    {
        $collection->register('admin-listener-route', 'Listener:Route@Admin')
            ->tag('listener');

        $collection->register('admin-listener-access', 'Listener:Main@Admin')
            ->subscribe('context')
            ->tag('listener');
    }
}