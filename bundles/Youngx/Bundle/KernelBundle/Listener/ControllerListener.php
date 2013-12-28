<?php

namespace Youngx\Bundle\KernelBundle\Listener;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Youngx\MVC\Application;
use Youngx\MVC\Event\GetResponseEvent;
use Youngx\MVC\ListenerRegistry;
use Youngx\MVC\Renderable;

class ControllerListener implements ListenerRegistry
{
    /**
     * @var Application
     */
    private $app;

    private $defaultControllerClass = 'Index';
    private $defaultControllerMethod = 'index';

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    public function controller(GetResponseEvent $event, Request $request)
    {
        $controller = $request->attributes->get('_controller');
        if ($controller) {
            $callback = $this->resolveController($controller, $request);
            $arguments = $this->app->arguments($callback, $request->attributes->all());
            $response = call_user_func_array($callback, $arguments);

            if ($response instanceof Renderable) {
                $this->app->dispatchWithMenu('kernel.layout', array(
                    $response
                ));

                $response = new Response($response);
            }

            if ($response instanceof Response) {
                $event->setResponse($response);
            }
        }
    }

    protected function resolveController($controller, Request $request)
    {
        if (is_array($controller) || (is_object($controller) && method_exists($controller, '__invoke'))) {
            return $controller;
        }

        if (false === strpos($controller, '.') && false === strpos($controller, '@')) {
            if (method_exists($controller, '__invoke')) {
                return new $controller;
            } elseif (function_exists($controller)) {
                return $controller;
            }
        }

        $callable = $this->createController($controller, $request);

        if (!is_callable($callable)) {
            throw new \InvalidArgumentException(sprintf('The controller for URI "%s" is not callable.', $request->getPathInfo()));
        }

        return $callable;
    }

    /**
     *
     * @param string $controller ::method@Bundle | @Bundle |
     *                           Controller@Bundle |
     *                           PathTo.Controller::method@Bundle
     * @param Request $request
     * @throws \InvalidArgumentException
     * @return callback
     */
    protected function createController($controller, Request $request)
    {
        if (preg_match('/^([a-zA-Z0-9\.]+)?(::([a-zA-Z0-9]+))?@([a-zA-Z0-9]+)$/', $controller, $match)) {
            $controller = $match[1];
            $method = $match[3];
            $bundle = $match[4];

            if (!$method) {
                $method =  $this->defaultControllerMethod;
            }

            $method .= 'Action';
            $controllerClass = $this->app->generateClass($bundle, $controller ? $controller : $this->defaultControllerClass, 'Controller');

            $request->attributes->set('_bundle', $bundle);

            $controller = $this->app->instantiate($controllerClass, $request->attributes->all());

            return array($controller, $method);
        } else {
            throw new \InvalidArgumentException(sprintf('Invalid Controller Format: [%s]', $controller));
        }
    }

    public static function registerListeners()
    {
        return array(
            'kernel.controller' => 'controller'
        );
    }
}