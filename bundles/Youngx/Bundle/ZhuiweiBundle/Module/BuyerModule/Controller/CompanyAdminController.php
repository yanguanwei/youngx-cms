<?php

namespace Youngx\Bundle\ZhuiweiBundle\Module\BuyerModule\Controller;

use Youngx\Bundle\ZhuiweiBundle\Module\BuyerModule\Entity\CompanyEntity;
use Youngx\MVC\Context;

class CompanyAdminController
{
    public function indexAction(Context $context)
    {
        return $context->actionResponse('Table:CompanyAdmin@Zhuiwei:Buyer');
    }

    public function baseAction(Context $context)
    {
        return $context->actionResponse('Table:BaseCompanyAdmin@Zhuiwei:Buyer');
    }

    public function editAction(Context $context, CompanyEntity $company)
    {
        return $context->actionResponse('Form:CompanyAdmin@Zhuiwei:Buyer', array(
                'company' => $company
            ));
    }

    public function deleteAction(Context $context)
    {
        return $context->actionResponse('Action:CompanyDelete@Zhuiwei:Buyer');
    }

    public function photoAction(Context $context, CompanyEntity $company)
    {
        return $context->actionResponse('Form:CompanyPhotoAdmin@Zhuiwei:Buyer', array(
                'company' => $company
            ));
    }

    public function photoDeleteAction(Context $context, CompanyEntity $company)
    {
        return $context->actionResponse('Action:CompanyPhotoDelete@Zhuiwei:Buyer', array(
                'company' => $company
            ));
    }
}