<?php

namespace Youngx\Bundle\AdminBundle\Module\VocabularyModule;

use Youngx\DI\ContainerBuilder;
use Youngx\DI\DefinitionCollection;

class VocabularyModuleBuilder extends ContainerBuilder
{
    public function collect(DefinitionCollection $collection)
    {
        $collection->register('term.listener.main', __NAMESPACE__ . '\Listener\MainListener')
            ->subscribe('context')
            ->tag('listener');

        $collection->register('term.listener.collect', __NAMESPACE__ . '\Listener\CollectListener')
            ->tag('listener');
    }
}