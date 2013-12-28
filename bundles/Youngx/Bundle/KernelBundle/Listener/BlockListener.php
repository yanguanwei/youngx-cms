<?php

namespace Youngx\Bundle\KernelBundle\Listener;

use Youngx\MVC\Menu\Menu;
use Youngx\EventHandler\Event\GetSortableArrayEvent;
use Youngx\EventHandler\Registration;
use Youngx\MVC\Context;

class BlockListener implements Registration
{
    /**
     * @var Context
     */
    protected $context;

    public function setContext(Context $context)
    {
        $this->context = $context;
    }

    public function debug(GetSortableArrayEvent $event)
    {
        if ($this->context->app()->isDebug()) {
            $queries = $this->context->db()->getQueries();
            if ($queries) {
                $s = '<pre>'.implode("\n", $queries).'</pre>';
                $event->set('queries', $s, 100);
            }
            $endTime = microtime(true);
            $event->set('runtime', sprintf('<pre>Runtime: %s</pre>', (($endTime - $this->context->app()->getStartTime()) * 1000) . 'ms'), 101);
        }
    }

    public function breadcrumbs(GetSortableArrayEvent $event)
    {
        $breadcrumbs = array();
        $menu = $this->context->request()->getMenu();
        if ($menu) {
            $name = $this->context->request()->getRouteName();
            $router = $this->context->router();
            while ($name) {
                $menu = $router->getMenu($name);
                array_unshift($breadcrumbs, array(
                        'label' => $menu->getLabel(),
                        'url' => $this->context->generateUrlWithCurrent($name)
                    ));
                $name = $menu->getParent();
            }
        }

        $event->set('breadcrumbs', $this->context->widget('Breadcrumbs', array(
                    '#breadcrumbs' => $breadcrumbs
                )));
    }

    public function formatSubtitleConfig($subtitle)
    {
        $this->context->block('title', $subtitle);
    }

    public function renderTitleBlock(GetSortableArrayEvent $event)
    {
        $titles = $event->all();
        foreach ($titles as $i => $title) {
            if (is_array($title)) {
                $titles[$i] = implode(" | ", $title);
            }
        }
        return preg_replace('/<\/?.+?>/', '', implode(" | ", $titles));
    }

    public function renderBlock(GetSortableArrayEvent $event)
    {
        return implode("\n", $event->all());
    }

    public static function registerListeners()
    {
        return array(
            'kernel.renderable.config#subtitle' => 'formatSubtitleConfig',
            'kernel.block#breadcrumbs' => 'breadcrumbs',
            'kernel.block#body' => array('debug', 1024),
            'kernel.block.render' => 'renderBlock',
            'kernel.block.render#title' => 'renderTitleBlock'
        );
    }
}