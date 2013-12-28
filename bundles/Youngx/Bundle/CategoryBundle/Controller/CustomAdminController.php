<?php

namespace Youngx\Bundle\CategoryBundle\Controller;

use Youngx\Bundle\CategoryBundle\Entity\CategoryCustomEntity;
use Youngx\Bundle\CategoryBundle\Entity\CategoryEntity;
use Youngx\MVC\Context;

class CustomAdminController
{
    public function indexAction(Context $context, CategoryEntity $category)
    {
        return $context->actionResponse('Table:CategoryCustomAdmin@Category', array(
                'category' => $category
            ));
    }

    public function addAction(Context $context, CategoryEntity $category = null)
    {
        return $context->actionResponse('Form:MultipleCategoryCustomAdmin@Category', $category ? array(
                'category' => $category
            ) : array());
    }

    public function editAction(Context $context, CategoryCustomEntity $category_custom)
    {
        return $context->actionResponse('Form:CategoryCustomAdmin@Category', array(
                'category_custom' => $category_custom
            ));
    }

    public function deleteAction(Context $context)
    {
        return $context->actionResponse('Action:CategoryCustomDelete@Category');
    }
}