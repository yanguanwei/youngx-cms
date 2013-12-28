<?php

namespace Youngx\Bundle\CategoryBundle\Listener;

use Youngx\EventHandler\Registration;
use Youngx\MVC\Database\EntityCollection;
use Youngx\MVC\Menu\Menu;
use Youngx\MVC\Menu\MenuCollection;

class CollectListener implements Registration
{
    public function collectEntity(EntityCollection $collection)
    {
        $collection->add('Entity:Category@Category');
//        $collection->add('Entity:CategoryCustom@Category')
//            ->relate('category', 'category', array('category_id' => 'id'), 'many_one', 'custom_categories');
    }

    public function collectMenu(MenuCollection $collection)
    {
        $collection->add('category-ajax-cxselect', '/category/ajax/cxselect.json', '地区数据', 'Ajax::cxselect@Category')
            ->setAccess('category-ajax-select');

        $admin = $collection->getCollection('admin');

        $admin->add('category-admin', '/settings/category', '分类', 'Admin@Category', Menu::MENU_ROOT)
            ->addAttribute('#icon', 'tags');

        $admin->add('category-list', '/settings/category/list', '分类列表', 'Admin@Category', Menu::MENU);

        $admin->add('category-add', '/settings/category/add/{parent}', '添加分类', 'Admin::add@Category', Menu::MENU)
            ->setDefault('parent', '0')
            ->setRequirement('parent', '\d+', 'category');

        $admin->add('category-edit', '/settings/category/{category}/edit', '编辑分类', 'Admin::edit@Category')
            ->setRequirement('category', '\d+', 'category');

        $admin->add('category-delete', '/settings/category/delete', '删除分类', 'Admin::delete@Category');
/*
        $admin->add('category-custom-admin', '/settings/category/custom/list/{category}', '自定义分类', 'CustomAdmin@Category')
            ->setRequirement('category', '\d+', 'category');

        $admin->add('category-custom-admin-add', '/settings/category/custom/add/{category}', '添加自定义分类', 'CustomAdmin::add@Category', Menu::MENU)
            ->setDefault('category', '0')
            ->setRequirement('category', '\d+', 'category');

        $admin->add('category-custom-admin-edit', '/settings/category/custom/{category_custom}/edit', '编辑自定义分类', 'CustomAdmin::edit@Category')
            ->setRequirement('category_custom', '\d+', 'category_custom');

        $admin->add('category-custom-admin-delete', '/settings/category/custom/delete', '删除自定义分类', 'CustomAdmin::delete@Category');
    */
    }

    public static function registerListeners()
    {
        return array(
            'kernel.entity.collect' => 'collectEntity',
            'kernel.menu.collect' => 'collectMenu',
        );
    }
}