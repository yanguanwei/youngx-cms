<?php

namespace Youngx\Bundle\ArchiveBundle\Module\NewsModule\Listener;

use Youngx\EventHandler\Registration;
use Youngx\MVC\Database\EntityCollection;
use Youngx\MVC\Menu\Menu;
use Youngx\MVC\Menu\MenuCollection;

class CollectListener implements Registration
{
    public function collectEntity(EntityCollection $collection)
    {
        $collection->add('Entity:News@Archive:News')
            ->relate('archive', 'archive', array('id' => 'id'), 'one_one', 'news');
    }

    public function collectMenu(MenuCollection $collection)
    {
        $admin = $collection->getCollection('admin');

        $admin->add('news-admin', '/content/news', '资讯管理', 'Admin@Archive:News', Menu::MENU);
        $admin->add('news-admin-list', '/content/news/list', '资讯列表', 'Admin@Archive:News', Menu::MENU);

        $admin->add('news-add', '/content/news/add', '添加资讯', 'Admin::add@Archive:News', Menu::MENU);
            //->setRequirement('channel', '\d+', 'channel')
            //->setDefault('channel', 0);

        $admin->add('news-edit', '/content/news/{news}/edit', '编辑资讯', 'Admin::edit@Archive:News')
            ->setRequirement('news', '\d+', 'news');

        $admin->add('news-delete', '/content/news/delete', '删除资讯', 'Admin::delete@Archive:News');
    }

    public static function registerListeners()
    {
        return array(
            'kernel.menu.collect' => 'collectMenu',
            'kernel.entity.collect' => 'collectEntity'
        );
    }
}