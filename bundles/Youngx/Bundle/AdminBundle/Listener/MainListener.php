<?php

namespace Youngx\Bundle\AdminBundle\Listener;

use Youngx\EventHandler\Registration;
use Youngx\MVC\Assets;
use Youngx\MVC\Context;
use Youngx\MVC\Event\GetResponseEvent;
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

    public function checkAccessForAdminRole()
    {
        if ($this->context->identity()->hasRole(Identity::ROLE_ADMINISTRATOR)) {
            return true;
        }
    }

    public function redirectAdminResponse(GetResponseEvent $event)
    {
        $event->setResponse($this->context->redirectResponse($this->context->generateUrl('admin')));
    }

    public function redirectAdminLoginResponse(GetResponseEvent $event)
    {
        if (!$this->context->identity()->isLogged()) {
            $event->setResponse($this->context->redirectResponse(
                    $this->context->generateUrl('admin-login', array(
                            'returnUrl' => $this->context->request()->getUri()
                        ))
                ));
        }
    }

    public function registerCoreAssets(Assets $assets)
    {
        $assets->registerStyleUrl('admin-common', 'Admin/common.css');
    }

    public static function registerListeners()
    {
        return array(
            'kernel.access@menu-group:admin' => 'checkAccessForAdminRole',
            'kernel.access.deny#user-login@menu-group:admin' => 'redirectAdminResponse',
            'kernel.access.deny@menu-group:admin' => 'redirectAdminLoginResponse',
            'kernel.assets@menu-group:admin' => 'registerCoreAssets',
        );
    }
}