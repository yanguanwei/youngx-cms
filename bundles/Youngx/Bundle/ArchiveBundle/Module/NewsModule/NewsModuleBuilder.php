<?php

namespace Youngx\Bundle\ArchiveBundle\Module\NewsModule;

use Youngx\DI\ContainerBuilder;
use Youngx\DI\DefinitionCollection;

class NewsModuleBuilder extends ContainerBuilder
{
    public function collect(DefinitionCollection $collection)
    {
        $collection->register('news.listener.collect', 'Listener:Collect@Archive:News')
            ->tag('listener');

        $collection->register('news.listener.admin', 'Listener:Admin@Archive:News')
            ->subscribe('context')
            ->tag('listener');
    }
}