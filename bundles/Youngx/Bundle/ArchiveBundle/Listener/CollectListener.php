<?php

namespace Youngx\Bundle\ArchiveBundle\Listener;

use Youngx\EventHandler\Registration;
use Youngx\MVC\Database\EntityCollection;

class CollectListener implements Registration
{
    public function collectEntity(EntityCollection $collection)
    {
        $collection->add('Entity:Archive@Archive')
            ->relate('channel', 'channel', array('channel_id' => 'id'), 'many_one', 'archives');
    }

    public static function registerListeners()
    {
        return array(
            'kernel.entity.collect' => 'collectEntity'
        );
    }
}