<?php

namespace Youngx\Bundle\KernelBundle\Service;

use Youngx\MVC\Context;
use Youngx\MVC\Menu\Menu;

class WebService
{
    /**
     * @var Context
     */
    protected $context;

    public function setContext(Context $context)
    {
        $this->context = $context;
    }

    public function getBreadcrumbs()
    {
        $breadcrumbs = array();
        $menu = $this->context->request()->getMenu();
        if ($menu) {
            $name = $this->context->request()->getRouteName();
            $attributes = $this->context->request()->attributes->all();
            $router = $this->context->router();
            $breadcrumbs[] = array(
                'label' => $menu->getLabel(),
                'url' => $router->generate($name, $attributes)
            );
            $parts = explode('/', trim($menu->getPath(), '/'));
            while (array_pop($parts)) {
                if ($route = $this->context->matchRoute($path = '/' . implode('/', $parts))) {
                    $matched = $router->getMenu($route);
                    array_unshift($breadcrumbs, array(
                            'label' => $matched->getLabel(),
                            'url' => $path
                        ));
                }
            }
        }
        return $breadcrumbs;
    }

    public function getNestedMenus($name)
    {
        $items = array();
        $this->parseNestedMenu($name, $items);
        return $items;
    }

    /**
     * @param $parent
     * @param array $items
     */
    protected function parseNestedMenu($parent, array &$items)
    {
        $router = $this->context->router();
        foreach ($router->getSubmenus($parent) as $name => $menu) {
            if ($menu->isMenu()) {
                $items[$name] = array(
                    'label' => $menu->getLabel(),
                    'url' => $this->context->generateUrlWithCurrent($name),
                    'attributes' => $menu->getAttributes()
                );
                if ($router->hasSubmenus($name)) {
                    $items[$name]['subMenus'] = array();
                    $this->parseNestedMenu($name, $items[$name]['subMenus']);
                }
            }
        }
    }
}