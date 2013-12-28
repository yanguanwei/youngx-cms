<?php

namespace Youngx\Bundle\KernelBundle\Listener;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Exception\MethodNotAllowedException;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Youngx\MVC\Application;
use Youngx\MVC\Context;
use Youngx\MVC\Event\GetResponseEvent;
use Youngx\MVC\Exception\HttpException;
use Youngx\MVC\Exception\MethodNotAllowedHttpException;
use Youngx\MVC\Exception\NotFoundHttpException;
use Youngx\MVC\ListenerRegistry;

class RoutingListener implements ListenerRegistry
{
    /**
     * @var Context
     */
    protected $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    public function routing(GetResponseEvent $event, Request $request)
    {
        $router = $this->app->router();
        try {
            $attributes = $router->match($request->getPathInfo() === '/' ? $request->getPathInfo() : rtrim($request->getPathInfo(), '/'));
            $routeName = $attributes['_route'];
            $routeParams = $attributes;
            unset($routeParams['_route']);
            $attributes['_route_params'] = $routeParams;

            $menu = $router->getMenu($routeName);

            $attributes['_controller'] = $menu->getController();

            $route = $router->getRoute($routeName);
            foreach ($menu->getLoaders() as $key => $loader) {
                if (isset($attributes[$key])) {
                    $value = $this->app->valueOf(array("kernel.menu.loader#{$loader}", 'kernel.menu.loader'),  $attributes[$key], $loader, $key);
                    if (null !== $value || $route->hasDefault($key)) {
                        $attributes[$key] = $value;
                    } else {
                        throw new ResourceNotFoundException();
                    }
                }
            }

            $menuGroups[] = $current = $menu->getGroup();
            while ($parent = $router->getMenuGroupParent($current)) {
                $menuGroups[] = $current = $parent;
            }
            $attributes['_groups'] = $menuGroups;

            $request->attributes->add($attributes);

            $accessible = $menu->getAccessible();

            if ($accessible === true) {
                return ;
            }

            if ($accessible === false) {
                $this->throwHttpException($event);
            }

            $events = array();
            if ($accessible) { $events[] = "kernel.access#{$accessible}";}
            $events[] = 'kernel.access';
            if (true !== $this->app->dispatchWithMenu($events, array($event, $request, $accessible))) {
                $events = array();
                if ($accessible) { $events[] = "kernel.access.deny#{$accessible}";}
                $events[] = 'kernel.access.deny';
                $this->app->dispatchWithMenu($events, array($event, $request, $accessible));
                $this->throwHttpException($event);
            }
        } catch (ResourceNotFoundException $e) {
            $message = sprintf('No route found for "%s %s"', $request->getMethod(), $request->getPathInfo());
            throw new NotFoundHttpException($message, $e);
        } catch (MethodNotAllowedException $e) {
            $message = sprintf('No route found for "%s %s": Method Not Allowed (Allow: %s)',
                $request->getMethod(), $request->getPathInfo(), strtoupper(implode(', ', $e->getAllowedMethods()))
            );
            throw new MethodNotAllowedHttpException($e->getAllowedMethods(), $message, $e);
        }
    }

    protected function throwHttpException(GetResponseEvent $event)
    {
        if (!$event->hasResponse()) {
            if ($this->app->identity()->isLogged()) {
                throw new HttpException(403, 'Access Denied.');
            } else {
                throw new HttpException(401, 'Not Authenticated.');
            }
        }
    }

    public function load($id, $entityType)
    {
        if ($this->app->schema()->hasEntityType($entityType)) {
            return $this->app->repository()->load($entityType, $id);
        }
    }

    public static function registerListeners()
    {
        return array(
            'kernel.routing' => 'routing',
            'kernel.menu.loader' => 'load'
        );
    }
}