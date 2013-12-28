<?php

namespace Youngx\Bundle\UserBundle\Module\RoleModule\Listener;

use Youngx\EventHandler\Registration;
use Youngx\MVC\Database\EntityCollection;
use Youngx\MVC\Menu\Menu;
use Youngx\MVC\Menu\MenuCollection;

class CollectListener implements Registration
{
    public function collectMenu(MenuCollection $collection)
    {
        $admin = $collection->getCollection('admin');

        $admin->add('user-role-permissions', '/user/permissions', '权限', 'Admin::permission@User:Role', Menu::MENU);
        $admin->add('user-role-add', '/user/role/add', '添加角色', 'Admin::add@User:Role');
        $admin->add('user-role-edit', '/user/role/{role}/edit', '编辑角色', 'Admin::add@User:Role')
            ->setRequirement('role', '\d+', 'role');
    }

    public function collectEntity(EntityCollection $collection)
    {
        $collection->add('Entity:Role@User:Role');
    }

    public static function registerListeners()
    {
        return array(
            'kernel.menu.collect' => 'collectMenu',
            'kernel.entity.collect' => 'collectEntity'
        );
    }
}