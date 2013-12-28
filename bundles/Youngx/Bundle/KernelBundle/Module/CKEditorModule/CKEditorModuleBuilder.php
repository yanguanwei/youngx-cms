<?php

namespace Youngx\Bundle\KernelBundle\Module\CKEditorModule;

use Youngx\DI\ContainerBuilder;
use Youngx\DI\DefinitionCollection;

class CKEditorModuleBuilder extends ContainerBuilder
{
    public function collect(DefinitionCollection $collection)
    {
        $collection->register('ckeditor.listener.input', __NAMESPACE__ . '\Listener\InputListener')
            ->tag('listener')
            ->subscribe('context');
    }
}