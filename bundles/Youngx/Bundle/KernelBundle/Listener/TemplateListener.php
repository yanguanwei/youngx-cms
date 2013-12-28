<?php

namespace Youngx\Bundle\KernelBundle\Listener;

use Symfony\Component\Routing\Generator\UrlGenerator;
use Youngx\EventHandler\Registration;
use Youngx\MVC\Application;
use Youngx\MVC\Form;
use Youngx\MVC\Template;

class TemplateListener implements Registration
{
    /**
     * @var Application
     */
    private $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    public function assets()
    {
        return $this->app->assets();
    }

    public function asset_script(Template $template, $key, $path, $sort = 0)
    {
        return $this->app->assets()->registerScriptUrl($key, $this->asset_url($template, $path), $sort);
    }

    public function asset_style(Template $template, $key, $path, $sort = 0)
    {
        return $this->app->assets()->registerStyleUrl($key, $this->asset_url($template, $path), $sort);
    }

    public function asset_style_code(Template $template, $key, $code, $sort = 0)
    {
        return $this->app->assets()->registerStyleCode($key, $code, $sort);
    }

    public function asset_script_code(Template $template, $key, $code, $sort = 0)
    {
        return $this->app->assets()->registerScriptCode($key, $code, $sort);
    }

    public function asset_package(Template $template, $package, $version = null)
    {
        return $this->app->assets()->registerPackage($package, $version);
    }

    public function asset_url(Template $template, $path)
    {
        if ($path[0] == '/') {
            $path = substr($path, 1);
        } else {
            $prefix = $template->getBundle()->getName();
            $path = "{$prefix}/{$path}";
        }

        return $this->app->assetUrl($path);
    }
    
    public function cache()
    {
        $cache = $this->app->cache();
        $args = func_get_args();
        if (!$args) {
            return $cache;
        }

        switch (count($args)) {
            case 1:
                return $cache->fetch($args[0]);
            case 2:
                $cache->save($args[0], $args[1]);
                return $cache;
            case 3:
                $cache->save($args[0], $args[1], $args[2]);
                return $cache;
        }

    }

    public function html(Template $template, $html, array $attributes = array())
    {
        return $this->app->html($html, $attributes);
    }

    public function widget(Template $template, $name, array $config = array())
    {
        return $this->app->widget($name, $config);
    }

    public function query(Template $template, $key, $default = null)
    {
        return $this->app->request()->query->get($key, $default);
    }

    public function post(Template $template, $key, $default = null)
    {
        return $this->app->request()->request->get($key, $default);
    }

    public function request()
    {
        return $this->app->request();
    }

    public function cookie(Template $template, $key, $default = null)
    {
        return $this->app->request()->cookies->get($key, $default);
    }

    public function session(Template $template, $key, $default = null)
    {
        return $this->app->session()->get($key, $default);
    }

    public function server(Template $template, $key, $default = null)
    {
        return $this->app->request()->server->get($key, $default);
    }

    public function url(Template $template, $name, array $parameters = array(), $referenceType = UrlGenerator::ABSOLUTE_PATH)
    {
        return $this->app->generateUrl($name, $parameters, $referenceType);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface
     */
    protected function flash()
    {
        return $this->app->flash();
    }

    public function flash_messages()
    {
        return $this->flash()->all();
    }

    public function identity()
    {
        return $this->app->identity();
    }

    public function repository()
    {
        return $this->app->repository();
    }

    public static function registerListeners()
    {
        return array(
            'kernel.template.call.asset_style' => 'asset_style',
            'kernel.template.call.asset_style_code' => 'asset_style_code',
            'kernel.template.call.asset_script_code' => 'asset_script_code',
            'kernel.template.call.asset_script' => 'asset_script',
            'kernel.template.call.asset_url' => 'asset_url',
            'kernel.template.call.asset_package' => 'asset_package',
            'kernel.template.call.assets' => 'assets',
            'kernel.template.call.cache' => 'cache',
            'kernel.template.call.block' => 'block',
            'kernel.template.call.form' => 'form',
            'kernel.template.call.html' => 'html',
            'kernel.template.call.table' => 'table',
            'kernel.template.call.tab' => 'tab',
            'kernel.template.call.widget' => 'widget',
            'kernel.template.call.query' => 'query',
            'kernel.template.call.request' => 'request',
            'kernel.template.call.cookie' => 'cookie',
            'kernel.template.call.session' => 'session',
            'kernel.template.call.server' => 'server',
            'kernel.template.call.url' => 'url',
            'kernel.template.call.flash_messages' => 'flash_messages',
            'kernel.template.call.identity' => 'identity',
            'kernel.template.call.repository' => 'repository',
        );
    }
}