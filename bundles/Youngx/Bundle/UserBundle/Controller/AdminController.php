<?php

namespace Youngx\Bundle\UserBundle\Controller;

use Youngx\MVC\Context;

class AdminController
{
    public function indexAction(Context $context)
    {
        return $context->actionResponse('Table:UserAdmin@User');
    }

    public function deleteAction(Context $context)
    {
        return $context->actionResponse('Action:UserDelete@User');
    }
}