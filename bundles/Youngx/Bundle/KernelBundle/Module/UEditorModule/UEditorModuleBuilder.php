<?php

namespace Youngx\Bundle\KernelBundle\Module\UEditorModule;

use Youngx\DI\ContainerBuilder;
use Youngx\DI\DefinitionCollection;

class UEditorModuleBuilder extends ContainerBuilder
{
    public function collect(DefinitionCollection $collection)
    {
        $collection->register('ueditor.listener.view', __NAMESPACE__ . '\Listener\ViewListener')
            ->tag('listener')
            ->subscribe('context');
    }
}