<?php

namespace Youngx\Bundle\CategoryBundle;

use Youngx\DI\ContainerBuilder;
use Youngx\DI\DefinitionCollection;

class CategoryBundleBuilder extends ContainerBuilder
{
    public function collect(DefinitionCollection $collection)
    {
        $collection->register('category-listener-collect', 'Listener:Collect@Category')
            ->tag('listener');

       $collection->register('category-listener-admin', 'Listener:Main@Category')
           ->subscribe('context')
            ->tag('listener');
    }
}