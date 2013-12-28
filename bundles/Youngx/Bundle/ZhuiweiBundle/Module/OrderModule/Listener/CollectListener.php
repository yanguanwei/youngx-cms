<?php

namespace Youngx\Bundle\ZhuiweiBundle\Module\OrderModule\Listener;

use Youngx\EventHandler\Registration;
use Youngx\MVC\Database\EntityCollection;
use Youngx\MVC\Menu\Menu;
use Youngx\MVC\Menu\MenuCollection;

class CollectListener implements Registration
{
    public function collectMenu(MenuCollection $collection)
    {
        $admin = $collection->getCollection('admin');

        $admin->add('order-admin', '/order', '订单管理', 'Admin@Zhuiwei:Order', Menu::MENU_ROOT)
            ->addAttribute('#icon', 'yen');

        $admin->add('order-admin-view', '/order/{order}', '查看订单', 'Admin::view@Zhuiwei:Order')
            ->setRequirement('order', '\d+', 'order');

        $admin->add('order-admin-edit', '/order/{order}/edit', '编辑订单', 'Admin::edit@Zhuiwei:Order')
            ->setRequirement('order', '\d+', 'order');

        $admin->add('order-admin-delete', '/order/delete', '删除订单', 'Admin::delete@Zhuiwei:Order');

    }

    public function collectEntity(EntityCollection $collection)
    {
        $collection->add('Entity:Order@Zhuiwei:Order');
        $collection->add('Entity:OrderDelivery@Zhuiwei:Order');
    }

    public static function registerListeners()
    {
        return array(
            'kernel.menu.collect' => 'collectMenu',
            'kernel.entity.collect' => 'collectEntity'
        );
    }
}