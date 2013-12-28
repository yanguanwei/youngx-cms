<?php

namespace Youngx\Bundle\KernelBundle\Listener;

use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Response;
use Youngx\EventHandler\Handler;
use Youngx\MVC\Context;
use Youngx\MVC\Event\GetResponseForExceptionEvent;
use Youngx\MVC\Exception\HttpException;
use Youngx\EventHandler\Registration;

class ExceptionListener implements Registration
{
    private $handler;
    private $logger;
    /**
     * @var Context
     */
    private $context;

    public function __construct(Handler $handler, LoggerInterface $logger)
    {
        $this->handler = $handler;
        $this->logger = $logger;
    }

    public function setContext(Context $context)
    {
        $this->context = $context;
    }

    public function deny(GetResponseForExceptionEvent $event)
    {
        $this->logger->info('access denied', array(
                'user' => array(
                    'id' => $this->context->identity()->getId(),
                    'name' => $this->context->identity()->getName()
                )
            ));

        $event->setResponse(new Response('Access Denied.'));
    }

    public function notFound(GetResponseForExceptionEvent $event)
    {
        $this->logger->info('not found', array(
                'uri' => $this->context->request()->getUri()
            ));

        $event->setResponse(new Response('Not Found.'));
    }

    public function handleException(GetResponseForExceptionEvent $event)
    {
        $e = $event->getException();
        if ($e instanceof HttpException) {
            $this->logger->info(sprintf('exception[%s]: %s', get_class($e), $e->getStatusCode()), array(
                    'uri' => $this->context->request()->getUri(),
                    'user' => array(
                        'id' => $this->context->identity()->getId(),
                        'name' => $this->context->identity()->getName()
                    )
                ));
            $this->handler->trigger("kernel.exception.http.{$e->getStatusCode()}", $event);
        } else {
            $this->logger->error(sprintf('exception[%s]: %s', get_class($e), $e->getMessage()), array(
                    'uri' => $this->context->request()->getUri(),
                    'user' => array(
                        'id' => $this->context->identity()->getId(),
                        'name' => $this->context->identity()->getName()
                    )
                ));
        }
    }

    public static function registerListeners()
    {
        return array(
            'kernel.exception' => 'handleException',
            'kernel.exception.http.403' => 'deny',
            'kernel.exception.http.404' => 'notFound'
        );
    }
}