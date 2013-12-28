<?php

namespace Youngx\Bundle\UserBundle\Listener;

use Youngx\MVC\Database\EntityCollection;
use Youngx\EventHandler\Registration;
use Youngx\MVC\Menu\Menu;
use Youngx\MVC\Menu\MenuCollection;

class CollectListener implements Registration
{
    public function collectMenu(MenuCollection $collection)
    {
        $collection->add('user-ajax-autocomplete', '/user/ajax/autocomplete', '用户选择列表', 'Ajax::autocomplete@User')
            ->setAccess('admin');

        $admin = $collection->getCollection('admin');

        $admin->add('user-admin', '/user', '用户', 'Admin@User', Menu::MENU_ROOT)
            ->addAttribute('#icon', 'user');

        $admin->add('user-delete', '/user/delete', '删除用户', 'Admin::delete@User');

        $admin->add('user-admin-account', '/user/account/{user}', '帐号信息', 'AccountAdmin@User', Menu::TAB_DEFAULT_SELF)
            ->setRequirement('user', '\d+', 'user')
            ->setDefault('user', 0)
            ->addAttributes(array(
                    '#icon' => 'user green bigger-120'
                ));

        $admin->add('user-admin-profile', '/user/account/profile/{user}', '个人资料', 'AccountAdmin::profile@User', Menu::TAB)
            ->setRequirement('user', '\d+', 'user')
            ->setDefault('user', 0)
            ->setParent('user-admin-account')
            ->addAttributes(array(
                    '#icon' => 'male bigger-120'
                ));

        $admin->add('user-admin-password', '/user/account/password/{user}', '修改密码', 'AccountAdmin::password@User', Menu::TAB)
            ->setRequirement('user', '\d+', 'user')
            ->setDefault('user', 0)
            ->setParent('user-admin-account')
            ->addAttributes(array(
                    '#icon' => 'key blue bigger-125'
                ));

        $admin->add('user-admin-delete', '/user/delete', '删除用户', 'Admin::delete@User');
    }

    public function collectEntity(EntityCollection $collection)
    {
        $collection->add('Entity:User@User');
        $collection->add('Entity:UserProfile@User');
        $collection->add('Entity:Role@User');
    }

    public static function registerListeners()
    {
        return array(
            'kernel.menu.collect' => 'collectMenu',
            'kernel.entity.collect' => 'collectEntity'
        );
    }
}