<?php

namespace Youngx\Bundle\UserBundle\Module\RetrievePasswordModule\Listener;

use Youngx\Kernel\Database\EntityCollection;
use Youngx\Kernel\Database\Schema;
use Youngx\Kernel\Handler\ListenerRegistration;
use Youngx\Kernel\MenuCollection;

class CollectListener implements ListenerRegistration
{
    public function collectEntity(EntityCollection $collection)
    {
        $collection->add('Youngx\Bundle\UserBundle\Module\RetrievePasswordModule\Entity\UserRetrievePassword', 'y_user_retrieve_password', 'uid')
            ->relate('user', 'user', array('uid' => 'uid'), Schema::ONE_MANY, 'retrieve_password_records');
    }

    public function collectMenu(MenuCollection $collection)
    {
        $collection->add('user.retrieve.password.apply', '/user/retrieve/password', '取回密码', 'RetrievePassword:Apply@User')
            ->setAccess(true);

        $collection->add('user.retrieve.password.check', '/user/retrieve/password/check', '设置密码', 'RetrievePassword:Check@User')
            ->setAccess(true);
    }

    public static function registerListeners()
    {
        return array(
            'kernel.menu.collect' => 'collectMenu',
            'kernel.entity.collect' => 'collectEntity'
        );
    }
}