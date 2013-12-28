<?php

namespace Youngx\Bundle\UserBundle\Module\ActivationModule\Listener;

use Youngx\EventHandler\Registration;
use Youngx\MVC\Database\EntityCollection;

class CollectListener implements Registration
{
    public function collectEntity(EntityCollection $collection)
    {
        $collection->add('Entity:UserActivation@User:Activation');
    }

    public static function registerListeners()
    {
        return array(
            'kernel.entity.collect' => 'collectEntity'
        );
    }
}