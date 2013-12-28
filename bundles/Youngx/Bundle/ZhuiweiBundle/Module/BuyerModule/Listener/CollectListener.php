<?php

namespace Youngx\Bundle\ZhuiweiBundle\Module\BuyerModule\Listener;

use Youngx\EventHandler\Registration;
use Youngx\MVC\Database\EntityCollection;
use Youngx\MVC\Menu\Menu;
use Youngx\MVC\Menu\MenuCollection;

class CollectListener implements Registration
{
    public function collectEntity(EntityCollection $collection)
    {
        $collection->add('Entity:Company@Zhuiwei:Buyer');
        $collection->add('Entity:Buyer@Zhuiwei:Buyer');
    }

    public function collectMenu(MenuCollection $collection)
    {
        $collection->add('company-ajax-autocomplete', '/company/ajax/autocomplete', '选择公司', 'Ajax::autocomplete@Zhuiwei:Buyer')
            ->setAccess(true);

        $admin = $collection->getCollection('admin');

        $admin->add('buyer-admin', '/user/buyer', '买家管理', 'Admin@Zhuiwei:Buyer', Menu::MENU_ROOT);
        $admin->add('buyer-admin-list', '/user/buyer/list', '买家列表', 'Admin@Zhuiwei:Buyer', Menu::MENU);

        $admin->add('buyer-admin-import', '/user/buyer/import-user', '导入买家', 'Import@Zhuiwei:Buyer', Menu::MENU);
        $admin->add('buyer-admin-import-company', '/user/buyer/import-company', '导入机构买家', 'ImportCompany@Zhuiwei:Buyer', Menu::MENU);
        $admin->add('buyer-admin-import-company-user', '/user/buyer/import-company-user', '导入机构个人买家', 'ImportCompanyUser@Zhuiwei:Buyer', Menu::MENU);

        //company admin
        $admin->add('company-admin', '/user/company', '公司管理', 'CompanyAdmin@Zhuiwei:Buyer', Menu::MENU_ROOT);
        $admin->add('company-admin-list', '/user/company/list', '公司列表', 'CompanyAdmin@Zhuiwei:Buyer', Menu::MENU);
        $admin->add('company-admin-base', '/user/company/base', '基础公司', 'CompanyAdmin::base@Zhuiwei:Buyer', Menu::MENU);
        $admin->add('company-admin-import', '/user/company/import', '导入基础公司', 'ImportBaseCompany@Zhuiwei:Buyer', Menu::MENU);

        $admin->add('company-admin-edit', '/user/company/{company}', '编辑公司', 'CompanyAdmin::edit@Zhuiwei:Buyer', Menu::TAB_DEFAULT_SELF)
            ->setRequirement('company', '\d+', 'company');

        $admin->add('company-admin-photo', '/user/company/{company}/photo', '公司证件', 'CompanyAdmin::photo@Zhuiwei:Buyer', Menu::TAB)
            ->setRequirement('company', '\d+', 'company');

        $admin->add('company-admin-photo-delete', '/user/company/{company}/photo/delete', '删除公司证件', 'CompanyAdmin::photoDelete@Zhuiwei:Buyer')
            ->setRequirement('company', '\d+', 'company');

        $admin->add('company-admin-delete', '/user/company/delete', '删除公司', 'CompanyAdmin::delete@Zhuiwei:Buyer');
    }

    public static function registerListeners()
    {
        return array(
            'kernel.entity.collect' => 'collectEntity',
            'kernel.menu.collect' => 'collectMenu'
        );
    }
}