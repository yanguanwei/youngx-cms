<?php

namespace Youngx\Bundle\KernelBundle;

use Monolog\Logger;
use Youngx\DI\Argument\IdReference;
use Youngx\DI\Argument\ParameterReference;
use Youngx\DI\ContainerBuilder;
use Youngx\DI\DefinitionProcessor;
use Youngx\DI\DefinitionCollection;
use Youngx\MVC\Application;

class KernelBundleBuilder extends ContainerBuilder
{
    /**
     * @var \Youngx\MVC\Application
     */
    private $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    public function collect(DefinitionCollection $collection)
    {
        $collection->register('web', 'Service:Web@Kernel', true)
            ->subscribe('context');

        $collection->register('handler', 'Youngx\MVC\Handler', array('Youngx\EventHandler\Handler', 'Youngx\MVC\Handler'))
            ->subscribe('container')
            ->subscribe('request');

        $database = $collection->register('database', 'Youngx\Database\Connection', true);

        $collection->register('repository', 'Youngx\Database\Repository', true);

        $schema = $collection->register('schema', 'Youngx\MVC\Database\Schema', array(
                'Youngx\Database\Schema',
                'Youngx\MVC\Database\Schema'
            ));
        $schema->setArgument('app', new IdReference('app'));

        $collection->register('dispatcher', 'Youngx\MVC\Dispatcher');
        $collection->register('router', 'Youngx\MVC\Router', true);
        $collection->register('session', 'Symfony\Component\HttpFoundation\Session\Session');
        $collection->register('user.identity.storage', 'Youngx\MVC\User\IdentityStorageInterface');
        $mailer = $collection->register('mailer', 'PHPMailer');

        $mailer->call('IsSMTP');
        $mailer->call('IsHTML');
        $mailerDefaultParameters = array(
            'CharSet' => 'UTF-8',
            'SMTPDebug' => false,
            'SMTPAuth' => true,
            //'SMTPKeepAlive' => true,
            //'SMTPSecure' => 'ssl'
        );
        foreach (array('Host','Port','Username','Password','SMTPDebug','CharSet','SMTPAuth','From','FromName',) as $key) {
            $mailer->set($key, new ParameterReference("mailer.{$key}", isset($mailerDefaultParameters[$key]) ? $mailerDefaultParameters[$key] : null));
        }

        $database->setArguments(array(
                new ParameterReference('db.name'),
                new ParameterReference('db.user'),
                new ParameterReference('db.passwd'),
                new ParameterReference('db.host'),
                new ParameterReference('db.type'),
                new ParameterReference('db.charset')
            ));

        //monolog
        $collection->register('monolog.stream', 'Monolog\Handler\StreamHandler')
            ->setArguments(array(
                    new ParameterReference('log.dir', $this->app->locate('app://log/'.$this->app->getEnvironment().'.log')),
                    new ParameterReference('log.level', $this->app->isDebug() ? Logger::DEBUG : Logger::WARNING)
                ));
        $monolog = $collection->register('monolog', 'Monolog\Logger', array(
                'Monolog\Logger', 'Psr\Log\LoggerInterface'
            ))->requireInput('name', 'kernel');
        $monolog->call('pushHandler', array(
                new IdReference('monolog.stream')
            ));

        //doctrine cache
        $collection->register('cache', 'Doctrine\Common\Cache\FilesystemCache', array(
                'Doctrine\Common\Cache\Cache',
                'Doctrine\Common\Cache\CacheProvider'
            ));

        $collection->register('kernel.listener.collect', __NAMESPACE__ . '\Listener\CollectListener')
            ->tag('listener');

        $collection->register('templating', 'Youngx\MVC\Yui\YuiEngine', true)
            ->subscribe('request')
            ->subscribe('app');

        $collection->register('kernel.listener.html', 'Listener:Html@Kernel')
            ->subscribe('context')
            ->tag('listener');

        $collection->register('kernel.listener.templating', 'Listener:Templating@Kernel')
            ->subscribe('context')
            ->tag('listener');

        $collection->register('assets', 'Youngx\MVC\Assets', true)
            ->subscribe('context')
            ->tag('listener');

        $collection->register('kernel.listener.input', 'Listener:Input@Kernel')
            ->subscribe('context')
            ->tag('listener');

        $collection->register('kernel.listener.exception', 'Listener:Exception@Kernel')
            ->subscribe('context')
            ->tag('listener');

        $collection->register('kernel.listener.routing', 'Listener:Routing@Kernel')
            ->subscribe('context')
            ->tag('listener');

        $collection->register('kernel.listener.controller', 'Listener:Controller@Kernel')
            ->subscribe('app')
            ->subscribe('container')
            ->subscribe('context')
            ->tag('listener');

        $collection->register('kernel.listener.validate', 'Listener:Validate@Kernel')
            ->tag('listener');

        $collection->register('kernel.listener.block', 'Listener:Block@Kernel')
            ->subscribe('context')
            ->tag('listener');
    }

    public function process(DefinitionProcessor $processor)
    {
        $handler = $processor->getDefinition('handler');
        foreach ($processor->getTaggedDefinitions('listener') as $id => $definition) {
            $handler->call('addServiceRegistration', array($id, $definition->getClass()));
        }

        $cache = $processor->getDefinition('cache');
        if ($cache->getClass() == 'Doctrine\Common\Cache\FilesystemCache') {
            $cache->setArguments(array(
                    $this->app->locate('cache://doctrine')
                ));
        }
    }
}