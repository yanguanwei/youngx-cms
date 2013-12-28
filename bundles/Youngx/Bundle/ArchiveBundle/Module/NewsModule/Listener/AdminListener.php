<?php

namespace Youngx\Bundle\ArchiveBundle\Module\NewsModule\Listener;

use Youngx\EventHandler\Event\GetValueEvent;
use Youngx\EventHandler\Registration;
use Youngx\MVC\Context;

class AdminListener implements Registration
{
    /**
     * @var Context $context
     */
    protected $context;

    public function setContext(Context $context)
    {
        $this->context = $context;
    }

    public static function registerListeners()
    {
        return array(
        );
    }
}