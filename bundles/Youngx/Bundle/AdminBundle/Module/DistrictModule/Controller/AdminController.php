<?php

namespace Youngx\Bundle\AdminBundle\Module\DistrictModule\Controller;

use Youngx\MVC\Context;

class AdminController
{
    public function indexAction(Context $context)
    {
        return $context->actionResponse('Table:DistrictTree@Admin:District');
    }

    public function importAction(Context $context)
    {
        return $context->actionResponse('Form:Import@Admin:District');
    }
}