<?php

namespace Youngx\Bundle\ArchiveBundle;

use Youngx\DI\ContainerBuilder;
use Youngx\DI\DefinitionCollection;

class ArchiveBundleBuilder extends ContainerBuilder
{
    public function collect(DefinitionCollection $collection)
    {
        $collection->register('archive.listener.collect', 'Listener:Collect@Archive')
            ->tag('listener');
    }
}