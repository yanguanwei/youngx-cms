<?php

namespace Youngx\Bundle\ZhuiweiBundle\Module\ProductModule\Listener;

use Youngx\EventHandler\Registration;
use Youngx\MVC\Database\EntityCollection;
use Youngx\MVC\Menu\Menu;
use Youngx\MVC\Menu\MenuCollection;

class CollectListener implements Registration
{
    public function collectMenu(MenuCollection $collection)
    {
        $collection->add('product-picture', '/product/picture-upload/{sort}/{product}', '上传产品图片', 'ProductPicture@Zhuiwei:Product')
            ->setRequirement('product', '\d+', 'product')
            ->setDefault('product', 0)
            ->setAccess('product-picture');

        $admin = $collection->getCollection('admin');

        $admin->add('product-admin', '/product', '产品管理', 'Admin@Zhuiwei:Product', Menu::MENU_ROOT)
            ->addAttribute('#icon', 'shopping-cart');

        $admin->add('product-admin-add', '/product/add', '添加产品', 'Admin::add@Zhuiwei:Product', Menu::MENU);
        $admin->add('product-admin-edit', '/product/{product}', '编辑产品', 'Admin::edit@Zhuiwei:Product')
            ->setRequirement('product', '\d+', 'product');

//        $admin->add('product-admin-category', '/product/{product}/category', '产品品类', 'Admin::category@Zhuiwei:Product', Menu::TAB)
//            ->setRequirement('product', '\d+', 'product');

//        $admin->add('product-admin-picture', '/product/{product}/picture', '产品图片', 'Admin::picture@Zhuiwei:Product', Menu::TAB)
//            ->setRequirement('product', '\d+', 'product');

        $admin->add('product-admin-delete', '/product/delete', '删除产品', 'Admin::delete@Zhuiwei:Product');


        $admin->add('product-admin-picture-default', '/product/{product}/picture/{file}', '删除产品', 'Admin::defaultPicture@Zhuiwei:Product')
            ->setRequirement('product', '\d+', 'product')
            ->setRequirement('file', '\d+', 'file');
    }

    public function collectEntity(EntityCollection $collection)
    {
        $collection->add('Entity:Product@Zhuiwei:Product');
        $collection->add('Entity:ProductDetail@Zhuiwei:Product')
            ->relate('product', 'product', array('product_id' => 'id'), 'one_one', 'detail');
        $collection->add('Entity:ProductPrice@Zhuiwei:Product');
    }

    public static function registerListeners()
    {
        return array(
            'kernel.menu.collect' => 'collectMenu',
            'kernel.entity.collect' => 'collectEntity'
        );
    }
}