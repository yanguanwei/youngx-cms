<?php

namespace Youngx\Bundle\AdminBundle\Module\DistrictModule\Listener;

use Youngx\EventHandler\Registration;
use Youngx\MVC\Database\EntityCollection;
use Youngx\MVC\Menu\Menu;
use Youngx\MVC\Menu\MenuCollection;

class CollectListener implements Registration
{
    public function collectMenu(MenuCollection $collection)
    {
        $collection->add('district-ajax-cxselect', '/district/ajax/cxselect.json', '地区数据', 'Ajax::cxselect@Admin:District');

        $admin = $collection->getCollection('admin');

        $admin->add('district-admin', '/settings/district', '地区管理', 'Admin@Admin:District', Menu::MENU_ROOT);
        $admin->add('district-admin-list', '/settings/district/list', '地区列表', 'Admin@Admin:District', Menu::MENU);
        $admin->add('district-import', '/settings/district/import', '地区导入', 'Admin::import@Admin:District', Menu::MENU);
    }

    public function collectEntity(EntityCollection $collection)
    {
        $collection->add('Entity:District@Admin:District');
    }

    public static function registerListeners()
    {
        return array(
            'kernel.menu.collect' => 'collectMenu',
            'kernel.entity.collect' => 'collectEntity'
        );
    }
}