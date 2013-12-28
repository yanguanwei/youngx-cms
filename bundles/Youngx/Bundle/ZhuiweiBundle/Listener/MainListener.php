<?php

namespace Youngx\Bundle\ZhuiweiBundle\Listener;

use Youngx\Bundle\UserBundle\Entity\UserEntity;
use Youngx\EventHandler\Event\GetSortableArrayEvent;
use Youngx\EventHandler\Registration;
use Youngx\MVC\Assets;
use Youngx\MVC\Context;
use Youngx\MVC\Event\GetResponseEvent;
use Youngx\MVC\RenderableResponse;
use Youngx\MVC\User\Identity;

class MainListener implements Registration
{
    /**
     * @var Context
     */
    protected $context;

    public function setContext(Context $context)
    {
        $this->context = $context;
    }

    public function registerCoreAssets(Assets $assets)
    {
        $assets->registerStyleUrl('global', 'Zhuiwei/css/global.css');
        $assets->registerStyleUrl('top', 'Zhuiwei/css/top.css');
        $assets->registerStyleUrl('foot', 'Zhuiwei/css/foot.css');

        $assets->registerPackage('jquery');
    }

    public function layout(RenderableResponse $response)
    {
        $this->context->block('content', $response->getContent());
        $response->setContent($this->context->render('layouts/main.html.yui@Zhuiwei'));
    }

    public function sellerLayout(RenderableResponse $response)
    {
        $this->context->block('seller-content', $response->getContent());
        $response->setContent($this->context->render('layouts/seller.html.yui@Zhuiwei'));
    }

    public function buyerLayout(RenderableResponse $response)
    {
        $this->context->block('buyer-content', $response->getContent());
        $response->setContent($this->context->render('layouts/buyer.html.yui@Zhuiwei'));
    }

    public function redirectUserLoginResponse(GetResponseEvent $event)
    {
        $event->setResponse($this->context->redirectResponse(
                $this->context->generateUrl($this->context->identity()->hasRole(Identity::ROLE_SELLER) ? 'seller-home' : 'buyer-home')
            ));
    }

    public function redirectUserRegisterResponse(GetResponseEvent $event)
    {
        $event->setResponse($this->context->redirectResponse(
                $this->context->generateUrl($this->context->identity()->hasRole(Identity::ROLE_SELLER) ? 'seller-home' : 'buyer-home')
            ));
    }

    public function checkSellerGroupAccess()
    {
        if ($this->context->identity()->hasRole(Identity::ROLE_SELLER)) {
            return true;
        }
    }

    public function checkBuyerGroupAccess()
    {
        if ($this->context->identity()->hasRole(Identity::ROLE_BUYER)) {
            return true;
        }
    }

    public function formatSellerSubtitleConfig($subtitle)
    {
        $this->context->block('subtitle', $subtitle);
    }

    public function renderMessages(GetSortableArrayEvent $event)
    {
        foreach ($this->context->flash()->all() as $type => $messages) {
            foreach ($messages as $i => $message) {
                $event->set("{$type}.{$i}", $this->context->html('message', array(
                            '#type' => $type,
                            '#content' => $message,
                        )), -100);
            }
        }
    }

    public static function registerListeners()
    {
        return array(
            'kernel.renderable.layout@menu-group:zhuiwei' => 'layout',
            'kernel.renderable.layout@menu-group:seller' => 'sellerLayout',
            'kernel.renderable.layout@menu-group:buyer' => 'buyerLayout',
            'kernel.access@menu-group:seller' => 'checkSellerGroupAccess',
            'kernel.access@menu-group:buyer' => 'checkBuyerGroupAccess',
            'kernel.access#product-picture' => 'checkSellerGroupAccess',
            'kernel.access#category-ajax-select' => 'checkSellerGroupAccess',
            'kernel.access.deny#user-login' => 'redirectUserLoginResponse',
            'kernel.access.deny#user-register' => 'redirectUserRegisterResponse',
            'kernel.assets@menu-group:zhuiwei' => 'registerCoreAssets',
            'kernel.renderable.config#subtitle@menu-group:seller' => 'formatSellerSubtitleConfig',
            'kernel.renderable.config#subtitle@menu-group:buyer' => 'formatSellerSubtitleConfig',
            'kernel.block#content@menu-group:zhuiwei' => 'renderMessages',
        );
    }
}