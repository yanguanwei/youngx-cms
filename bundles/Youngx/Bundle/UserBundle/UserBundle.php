<?php

namespace Youngx\Bundle\UserBundle;

use Youngx\MVC\Application;
use Youngx\MVC\BeanRegistry;
use Youngx\MVC\Bundle;
use Youngx\MVC\EntityCollection;
use Youngx\MVC\Event\GetResponseForExceptionEvent;
use Youngx\MVC\ListenerRegistry;
use Youngx\MVC\MenuCollection;
use Youngx\MVC\Support\CookieIdentityStorage;

class UserBundle extends Bundle implements BeanRegistry, ListenerRegistry
{
    public function registerIdentityStorageBean(Application $app)
    {
        return new CookieIdentityStorage($app);
    }

    public static function registerBeans()
    {
        return array(
            'identityStorage' => array(
                'Youngx\MVC\IdentityStorage'
            )
        );
    }

    public function onHttpException401(GetResponseForExceptionEvent $event)
    {
        $app = Application::getInstance();
        $event->setResponse($app->redirectResponse($app->generateUrl('user-login', array(
            'returnUrl' => $app->request()->getUri()
        ))));
    }

    public function onCollectMenu(MenuCollection $collection)
    {
        $collection->add('user-login', '/login', 'login', 'Login@User')
            ->setAccessible('user-login');


        $admin = $collection->getCollection('admin');
        $admin->setPrefix('/admin');
        $admin->add('admin-login', '/login', 'login', 'AdminLogin@User')
            ->setAccessible('user-login');
        $admin->add('admin-logout', '/logout', 'logout', 'AdminLogout@User')
            ->setAccessible('user-logout');
        $admin->add('admin-settings', '/settings', 'settings', 'Settings@User');
        $admin->add('user-admin-account', '/settings', 'settings', 'Settings@User');
        $admin->add('user-admin-password', '/settings', 'settings', 'Settings@User');
    }

    public function onUserLoginAccessible()
    {
        if (Application::getInstance()->identity()->isLogged()) {
            return false;
        }
        return true;
    }

    public function onCollectEntity(EntityCollection $collection)
    {
        $collection->add(__NAMESPACE__.'\Entity\UserEntity', 'user', 'y_user', 'uid', array(
            'uid', 'name', 'email', 'password', 'created_at', 'status'
        ));
    }

    public static function registerListeners()
    {
        return array(
            'kernel.exception.http#401' => 'onHttpException401',
            'kernel.access#user-login' => 'onUserLoginAccessible',
            'kernel.menu.collect' => 'onCollectMenu',
            'kernel.entity.collection' => 'onCollectEntity'
        );
    }
}
