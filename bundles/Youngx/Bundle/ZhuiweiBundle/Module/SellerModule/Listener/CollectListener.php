<?php

namespace Youngx\Bundle\ZhuiweiBundle\Module\SellerModule\Listener;

use Youngx\EventHandler\Registration;
use Youngx\MVC\Database\EntityCollection;
use Youngx\MVC\Menu\Menu;
use Youngx\MVC\Menu\MenuCollection;

class CollectListener implements Registration
{
    public function collectMenu(MenuCollection $collection)
    {
        $collection->add('seller-logo-upload', '/seller/logo-upload/{user}', '图片上传', 'SellerLogoUpload@Zhuiwei:Seller')
            ->setRequirement('user', '\d+', 'user');

        $admin = $collection->getCollection('admin');
        $admin->add('seller-admin', '/user/seller', '卖家管理', 'Admin@Zhuiwei:Seller', Menu::MENU_ROOT_TAB_DEFAULT);
        $admin->add('seller-admin-list', '/user/seller/list', '卖家列表', 'Admin@Zhuiwei:Seller', Menu::MENU_TAB);
        $admin->add('seller-admin-willing', '/user/seller/willing', '潜在客户', 'Admin::willing@Zhuiwei:Seller', Menu::MENU_TAB);
        $admin->add('seller-admin-vip', '/user/seller/vip', 'VIP客户', 'Admin::vip@Zhuiwei:Seller', Menu::MENU_TAB);

        $admin->add('seller-admin-vip-willing', '/user/seller/{user}/vip/willing', '有意成为付费客户', 'Admin::setWilling@Zhuiwei:Seller')
            ->setRequirement('user', '\d+', 'user');

        $admin->add('seller-admin-edit', '/user/seller/{user}/edit', '卖家信息', 'Admin::edit@Zhuiwei:Seller')
            ->setRequirement('user', '\d+', 'user');

        $admin->add('seller-import', '/user/seller/import', '导入卖家', 'ImportSeller@Zhuiwei:Seller', Menu::MENU);

        //factory admin
        $admin->add('factory-admin', '/user/factory', '工厂管理', 'FactoryAdmin@Zhuiwei:Seller', Menu::MENU_ROOT);
        $admin->add('factory-admin-list', '/user/factory/list', '工厂列表', 'FactoryAdmin@Zhuiwei:Seller', Menu::MENU);
        $admin->add('factory-admin-base', '/user/factory/base', '基础工厂', 'FactoryAdmin::base@Zhuiwei:Seller', Menu::MENU);
        $admin->add('factory-import', '/user/factory/import', '导入基础工厂', 'ImportFactory@Zhuiwei:Seller', Menu::MENU);

        $admin->add('factory-admin-edit', '/user/factory/{factory}', '编辑工厂', 'FactoryAdmin::edit@Zhuiwei:Seller', Menu::TAB_DEFAULT_SELF)
            ->setRequirement('factory', '\d+', 'factory');

        $admin->add('factory-admin-photo', '/user/factory/{factory}/photo', '工厂证件', 'FactoryAdmin::photo@Zhuiwei:Seller', Menu::TAB)
            ->setRequirement('factory', '\d+', 'factory');

        $admin->add('factory-admin-picture', '/user/factory/{factory}/picture', '厂房图片', 'FactoryAdmin::picture@Zhuiwei:Seller', Menu::TAB)
            ->setRequirement('factory', '\d+', 'factory');

        $admin->add('factory-admin-picture-delete', '/user/factory/{factory}/picture/delete', '删除厂房图片', 'FactoryAdmin::pictureDelete@Zhuiwei:Seller')
            ->setRequirement('factory', '\d+', 'factory');

        $admin->add('factory-admin-photo-delete', '/user/factory/{factory}/photo/delete', '删除工厂证件', 'FactoryAdmin::photoDelete@Zhuiwei:Seller')
            ->setRequirement('factory', '\d+', 'factory');

        $admin->add('factory-admin-delete', '/user/factory/delete', '删除工厂', 'FactoryAdmin::delete@Zhuiwei:Seller');
    }

    public function collectEntity(EntityCollection $collection)
    {
        $collection->add('Entity:Factory@Zhuiwei:Seller');
        $collection->add('Entity:Seller@Zhuiwei:Seller');
    }

    public static function registerListeners()
    {
        return array(
            'kernel.menu.collect' => 'collectMenu',
            'kernel.entity.collect' => 'collectEntity'
        );
    }
}