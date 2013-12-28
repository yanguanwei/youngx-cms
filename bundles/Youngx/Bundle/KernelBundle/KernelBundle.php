<?php

namespace Youngx\Bundle\KernelBundle;

use Symfony\Component\HttpFoundation\Session\Session;
use Youngx\Database\Connection;
use Youngx\MVC\Application;
use Youngx\MVC\Assets;
use Youngx\MVC\BeanRegistry;
use Youngx\MVC\Bundle;
use Youngx\MVC\EntityCollection;
use Youngx\MVC\Event\GetResponseForExceptionEvent;
use Youngx\MVC\Exception\HttpException;
use Youngx\MVC\ListenerRegistry;
use Youngx\MVC\MenuCollection;
use Youngx\MVC\Repository;
use Youngx\MVC\Router;
use Youngx\MVC\Schema;

class KernelBundle extends Bundle implements BeanRegistry, ListenerRegistry
{
    public function registerDatabaseBean(Application $app)
    {
        $database = new Connection(
            $app->getParameter('db.name'),
            $app->getParameter('db.user'),
            $app->getParameter('db.passwd'),
            $app->getParameter('db.host'),
            $app->getParameter('db.type', 'mysql'),
            $app->getParameter('db.charset', 'UTF8')
        );

        return $database;
    }

    public function registerRepository(Application $app)
    {
        $repository = new Repository($app);

        return $repository;
    }

    public function registerSchemaBean(Application $app)
    {
        $collection = new EntityCollection($app);
        $app->dispatch('kernel.entity.collect', array($collection));

        $schema = new Schema($collection->all());

        return $schema;
    }

    public function registerSessionBean()
    {
        $session = new Session();

        return $session;
    }

    public function registerAssetsBean()
    {
        $assets = new Assets($this->app);

        return $assets;
    }

    public static function registerBeans()
    {
        return array(
            'database' => 'Youngx\Database\Connection',
            'repository' => 'Youngx\MVC\Repository',
            'schema' => 'Youngx\MVC\Schema',
            'router' => 'Youngx\MVC\Router',
            'session' => 'Symfony\Component\HttpFoundation\Session\Session',
            'assets' => 'Youngx\MVC\Assets'
        );
    }

    public function registerRouterBean(Application $app)
    {
        $collection = new MenuCollection();
        $app->dispatch('kernel.menu.collect', array($collection));
        $router = new Router($collection, $app->request());

        return $router;
    }

    public function onCollectMenu(MenuCollection $collection)
    {
        $collection->add('home', '/', 'home', 'Index@Kernel');
    }

    public function onHandleException(GetResponseForExceptionEvent $event)
    {
        $e = $event->getException();
        if ($e instanceof HttpException) {
            $this->app->dispatch("kernel.exception.http#{$e->getStatusCode()}", array($event));
        }
    }

    public static function registerListeners()
    {
        return array(
            __NAMESPACE__ . '\Listener\RoutingListener',
            __NAMESPACE__ . '\Listener\ControllerListener',
            __NAMESPACE__ . '\Listener\TemplateListener',
            'kernel.menu.collect' => 'onCollectMenu',
            'kernel.exception' => 'onHandleException',
        );
    }
}