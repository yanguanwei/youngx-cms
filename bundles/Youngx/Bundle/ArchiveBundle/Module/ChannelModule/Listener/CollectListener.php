<?php

namespace Youngx\Bundle\ArchiveBundle\Module\ChannelModule\Listener;

use Youngx\EventHandler\Event\GetValueEvent;
use Youngx\EventHandler\Registration;
use Youngx\MVC\Database\EntityCollection;
use Youngx\MVC\Menu\Menu;
use Youngx\MVC\Menu\MenuCollection;
use Youngx\MVC\User\PermissionCollection;

class CollectListener implements Registration
{
    public function collectMenu(MenuCollection $collection)
    {
        $admin = $collection->getCollection('admin');

        $admin->add('content-admin', '/content', '内容', 'Admin@Archive:Channel', Menu::MENU_ROOT)
            ->addAttribute('#icon', 'archive');

        $admin->add('channel-admin', '/content/channel', '栏目管理', 'Admin@Archive:Channel', Menu::MENU);
        $admin->add('channel-admin-list', '/content/channel/list', '栏目列表', 'Admin@Archive:Channel', Menu::MENU);

        $admin->add('channel-add', '/content/channel/add/{parent}', '添加栏目', 'Admin::add@Archive:Channel', Menu::MENU)
            ->setRequirement('parent', '\d+', 'channel')
            ->setDefault('parent', 0);

        $admin->add('channel-edit', '/content/channel/{channel}/edit', '编辑', 'Admin::edit@Archive:Channel')
            ->setRequirement('channel', '\d+', 'channel');

        $admin->add('channel-delete', '/content/channel/delete', '删除', 'Admin::delete@Archive:Channel');

    }

    public function collectPermission(PermissionCollection $collection)
    {
        $collection->addPermissions('channel', array(
                'channel-add' => '添加栏目',
                'channel-edit' => '编辑栏目',
                'channel-delete' => '删除栏目'
            ), '栏目');
    }

    public function collectEntity(EntityCollection $collection)
    {
        $collection->add('Entity:Channel@Archive:Channel')
            ->relate('parent', 'channel', array('parent_id' => 'id'), 'many_one', 'children')
            ->relate('ancestor', 'channel', array('ancestor_id' => 'id'), 'many_one', 'descendants');
    }

    public static function registerListeners()
    {
        return array(
            'kernel.menu.collect' => 'collectMenu',
            'kernel.entity.collect' => 'collectEntity',
            'kernel.permission.collect' => 'collectPermission'
        );
    }
}