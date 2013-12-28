<?php

namespace Youngx\Bundle\AceBundle;

use Youngx\MVC\Bundle;
use Youngx\MVC\ListenerRegistry;
use Youngx\MVC\Renderable;

class AceBundle extends Bundle implements ListenerRegistry
{
    public function onAdminLayout(Renderable $renderable)
    {
        $renderable->wrap($this->app->render('layouts/main.html.php@Ace'));
    }

    public static function registerListeners()
    {
        return array(
            'kernel.layout@group:admin' => 'onAdminLayout',
        );
    }
}