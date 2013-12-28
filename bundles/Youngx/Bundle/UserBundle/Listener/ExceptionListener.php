<?php

namespace Youngx\Bundle\UserBundle\Listener;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Youngx\MVC\Context;
use Youngx\MVC\Event\GetResponseForExceptionEvent;
use Youngx\EventHandler\Registration;

class ExceptionListener implements Registration
{
    /**
     * @var Context
     */
    protected $context;

    public function setContext(Context $context)
    {
        $this->context = $context;
    }

    public function notLogged(GetResponseForExceptionEvent $event)
    {
        $event->setResponse(RedirectResponse::create($this->context->generateUrl('user-login', array(
                        'returnUrl' => $this->context->request()->getUri()
                    ))));
    }

    public static function registerListeners()
    {
        return array(
            'kernel.exception.http.401' => 'notLogged'
        );
    }
}