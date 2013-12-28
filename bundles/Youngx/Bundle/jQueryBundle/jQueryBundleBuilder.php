<?php

namespace Youngx\Bundle\jQueryBundle;

use Youngx\DI\ContainerBuilder;
use Youngx\DI\DefinitionCollection;

class jQueryBundleBuilder extends ContainerBuilder
{
    public function collect(DefinitionCollection $collection)
    {
        $collection->register('jquery.listener.kernel', 'Listener:Assets@jQuery')
            ->tag('listener');

        $collection->register('jquery.listener.format', 'Listener:Format@jQuery')
            ->subscribe('context')
            ->tag('listener');

        $collection->register('jquery.listener.input', 'Listener:Input@jQuery')
            ->subscribe('context')
            ->tag('listener');
    }
}