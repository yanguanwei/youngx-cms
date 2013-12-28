<?php

namespace Youngx\Bundle\AdminBundle\Module\VocabularyModule\Listener;

use Youngx\EventHandler\Registration;
use Youngx\MVC\Database\EntityCollection;
use Youngx\MVC\Menu\Menu;
use Youngx\MVC\Menu\MenuCollection;

class CollectListener implements Registration
{
    public function collectMenu(MenuCollection $collection)
    {
        $admin = $collection->getCollection('admin');

        $admin->add('vocabulary-admin', '/settings/vocabulary', '词汇表管理', 'Admin@Admin:Vocabulary', Menu::MENU);
        $admin->add('vocabulary-list', '/settings/vocabulary/list', '词汇表', 'Admin@Admin:Vocabulary', Menu::MENU);
        $admin->add('vocabulary-add', '/settings/vocabulary/add', '添加词汇表', 'Admin::add@Admin:Vocabulary', Menu::MENU);
        $admin->add('vocabulary-edit', '/settings/vocabulary/{vocabulary}/edit', '编辑词汇表', 'Admin::edit@Admin:Vocabulary')
            ->setRequirement('vocabulary', '\d+', 'vocabulary');
        $admin->add('vocabulary-delete', '/settings/vocabulary/delete', '删除词汇表', 'Admin::delete@Admin:Vocabulary');

        $admin->add('term-admin', '/settings/vocabulary/{vocabulary}/term', '术语表', 'TermAdmin@Admin:Vocabulary', Menu::TAB_DEFAULT_SELF)
            ->setRequirement('vocabulary', '\d+', 'vocabulary');

        $admin->add('term-add', '/settings/vocabulary/{vocabulary}/term/add', '添加术语', 'TermAdmin::add@Admin:Vocabulary', Menu::TAB)
            ->setRequirement('vocabulary', '\d+', 'vocabulary');

        $admin->add('term-edit', '/settings/vocabulary/term/{term}/edit', '编辑术语', 'TermAdmin::edit@Admin:Vocabulary')
            ->setRequirement('term', '\d+', 'term');

        $admin->add('term-delete', '/settings/vocabulary/term/delete', '删除术语', 'TermAdmin::delete@Admin:Vocabulary');

    }

    public function collectEntity(EntityCollection $collection)
    {
        $collection->add('Entity:Vocabulary@Admin:Vocabulary');
        $collection->add('Entity:Term@Admin:Vocabulary')
            ->relate('vocabulary', 'vocabulary', array('vocabulary_id' => 'id'), 'many_one', 'terms');
    }

    public static function registerListeners()
    {
        return array(
            'kernel.menu.collect' => 'collectMenu',
            'kernel.entity.collect' => 'collectEntity'
        );
    }
}