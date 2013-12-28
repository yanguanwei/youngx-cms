<?php

namespace Youngx\Bundle\AdminBundle\Module\FileModule\Controller;

use Youngx\MVC\Context;

class AdminController
{
    public function indexAction()
    {

    }

    public function deleteAction(Context $context)
    {
        return $context->actionResponse('Action:FileDelete@Admin:File');
    }
}