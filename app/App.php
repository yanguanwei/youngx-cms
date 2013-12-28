<?php

define('Y_TIME', time());

class App extends \Youngx\MVC\Application
{
    protected function registerBundles()
    {
        return array(
            new Youngx\Bundle\KernelBundle\KernelBundle(),
//            new Youngx\Bundle\jQueryBundle\jQueryBundle(),
//            new Youngx\Bundle\CategoryBundle\CategoryBundle(),
            new Youngx\Bundle\UserBundle\UserBundle(),
            new Youngx\Bundle\BootstrapBundle\BootstrapBundle(),
            new Youngx\Bundle\AceBundle\AceBundle(),
//            new Youngx\Bundle\AdminBundle\AdminBundle(),
//            new Youngx\Bundle\ZhuiweiBundle\ZhuiweiBundle(),
//            new Youngx\Bundle\ArchiveBundle\ArchiveBundle(),
        );
    }

    protected function initLocations()
    {
        $this->registerLocator('app', __DIR__)
            ->registerLocator('cache', "app://cache/{$this->getEnvironment()}")
            ->registerLocator('web', dirname(__DIR__) . '/web')
            ->registerLocator('public', 'web://public', '/public')
            ->registerLocator('assets', 'web://assets', '/assets');
    }

    protected function registerConfiguration()
    {
        $file = __DIR__ . "/config/main_{$this->getEnvironment()}.php";
        if (is_file($file)) {
            return include $file;
        }
        return array();
    }
}
