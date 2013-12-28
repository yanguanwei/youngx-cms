<?php

namespace Youngx\Bundle\AdminBundle\Controller;

use Youngx\MVC\Context;

class DashboardController
{
    public function indexAction(Context $context)
    {
        return $context->renderableResponse();
    }

    public function settingsAction(Context $context)
    {
        return $context->renderableResponse();
    }

    public function cacheAction(Context $context)
    {
        return $context->renderableResponse()
            ->addVariable('#subtitle', '缓存管理')
            ->setContent(var_export($context->cache()->getStats(), true));
    }

    public function clearCacheAction(Context $context)
    {
        $context->cache()->deleteAll();
        $context->flash()->add('success', '成功清除所有缓存！');
        @unlink($context->app()->getContainerPath());

        return $context->redirectResponse($context->generateUrl('admin-cache'));
    }
}