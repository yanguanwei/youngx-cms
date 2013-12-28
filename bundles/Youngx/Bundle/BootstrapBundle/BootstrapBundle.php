<?php

namespace Youngx\Bundle\BootstrapBundle;

use Youngx\MVC\Assets;
use Youngx\MVC\Bundle;
use Youngx\MVC\ListenerRegistry;

class BootstrapBundle extends Bundle implements ListenerRegistry
{
    public function onBootstrapPackage(Assets $assets)
    {
        $assets->registerScriptUrl('bootstrap', 'Bootstrap/js/bootstrap.js');
        $assets->registerStyleUrl('bootstrap', 'Bootstrap/css/bootstrap.css');
    }

    public static function registerListeners()
    {
        return array(
            'kernel.assets.package#bootstrap' => 'onBootstrapPackage',
        );
    }
}