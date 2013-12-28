<?php

namespace Youngx\Bundle\KernelBundle\Listener;

use Youngx\EventHandler\Registration;
use Youngx\MVC\Menu\MenuCollection;

class CollectListener implements Registration
{
    public function collectMenu(MenuCollection $collection)
    {
        $collection->add('captcha', '/captcha/{id}', '验证码图片', 'Index::captcha@Kernel')
            ->setDefault('id', '')
            ->setAccess(true);
    }

    public static function registerListeners()
    {
        return array(
            'kernel.menu.collect' => 'collectMenu'
        );
    }
}