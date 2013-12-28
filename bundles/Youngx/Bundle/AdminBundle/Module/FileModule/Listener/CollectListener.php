<?php

namespace Youngx\Bundle\AdminBundle\Module\FileModule\Listener;

use Youngx\EventHandler\Registration;
use Youngx\MVC\Database\EntityCollection;
use Youngx\MVC\Menu\MenuCollection;

class CollectListener implements Registration
{
    public function collectMenu(MenuCollection $collection)
    {
        $admin = $collection->getCollection('admin');
        $admin->add('file-admin', '/file', '文件列表', 'Admin@Admin:File');
        $admin->add('file-admin-delete', '/file/delete', '删除文件', 'Admin::delete@Admin:File');
    }

    public function collectEntity(EntityCollection $collection)
    {
        $collection->add('Entity:File@Admin:File');
    }

    public static function registerListeners()
    {
        return array(
            'kernel.menu.collect' => 'collectMenu',
            'kernel.entity.collect' => 'collectEntity'
        );
    }
}