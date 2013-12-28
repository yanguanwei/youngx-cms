<?php

namespace Youngx\Bundle\UserBundle\Module\AlipayModule\Listener;

use Youngx\Kernel\Handler\ListenerRegistration;
use Youngx\Kernel\MenuCollection;

class CollectListener implements ListenerRegistration
{
    public function collectMenu(MenuCollection $collection)
    {
        $collection->add('user.login.alipay', '/user/login/alipay', '支付宝快捷登录', 'Alipay:Login::index@User');
    }

    public static function registerListeners()
    {
        return array(
            'kernel.menu.collect' => 'collectMenu'
        );
    }
}