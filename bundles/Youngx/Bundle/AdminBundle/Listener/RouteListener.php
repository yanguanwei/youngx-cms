<?php

namespace Youngx\Bundle\AdminBundle\Listener;

use Youngx\EventHandler\Registration;
use Youngx\MVC\Menu\Menu;
use Youngx\MVC\Menu\MenuCollection;

class RouteListener implements Registration
{
    public function collectMenu(MenuCollection $collection)
    {
        $admin = $collection->getCollection('admin');

        $admin->setPrefix('/admin');

        $admin->add('admin', '', '管理首页', 'Dashboard@Admin');

        $admin->add('admin-login', '/login', '登录', 'Login@Admin')
            ->setAccess('user-login');

        $admin->add('admin-logout', '/logout', '退出', 'Logout@Admin')
            ->setAccess('user-logout');

        $admin->add('admin-dashboard', '/dashboard', '控制面板', 'Dashboard@Admin', Menu::MENU)
            ->setSort(-10)
            ->addAttribute('#icon', 'dashboard');

        $admin->add('admin-settings', '/settings', '设置', 'Dashboard::settings@Admin', Menu::MENU)
            ->setSort(100)
            ->addAttribute('#icon', 'cog');

        $admin->add('admin-cache', '/settings/cache', '缓存管理', 'Dashboard::cache@Admin', Menu::MENU_ROOT);
        $admin->add('admin-cache-stats', '/settings/cache/stats', '缓存信息', 'Dashboard::cache@Admin', Menu::MENU);
        $admin->add('admin-cache-clear', '/settings/cache/clear', '清除缓存', 'Dashboard::clearCache@Admin', Menu::MENU);
    }

    public static function registerListeners()
    {
        return array(
            'kernel.menu.collect' => 'collectMenu'
        );
    }
}