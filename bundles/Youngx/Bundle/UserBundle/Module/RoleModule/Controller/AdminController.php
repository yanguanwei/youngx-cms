<?php

namespace Youngx\Bundle\UserBundle\Module\RoleModule\Controller;

use Youngx\MVC\Context;

class AdminController
{
    public function addAction(Context $context)
    {
        return $context->actionResponse('Form:RoleAdmin@User:Role');
    }

    public function permissionAction(Context $context)
    {
        return $context->actionResponse('Form:PermissionAdmin@User:Role');
    }
}