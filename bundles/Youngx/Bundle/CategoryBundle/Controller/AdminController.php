<?php

namespace Youngx\Bundle\CategoryBundle\Controller;

use Youngx\Bundle\CategoryBundle\Entity\CategoryEntity;
use Youngx\MVC\Context;
use Youngx\MVC\Form;

class AdminController
{
    public $label;
    public $name;
    public $parent_id = 0;
    public $sort_num = 0;

    /**
     * @var CategoryEntity
     */
    protected $parent;

    /**
     * @var CategoryEntity
     */
    protected $category;

    public function indexAction(Context $context)
    {
        return $context->actionResponse('Table:CategoryAdminTree@Category');
    }

    public function addAction(Context $context, CategoryEntity $parent = null)
    {
        return $context->actionResponse('Form:MultipleCategoryAdmin@Category', $parent ? array(
                'parent' => $parent
            ) : array());
    }

    public function editAction(Context $context, CategoryEntity $category)
    {
        return $context->actionResponse('Form:CategoryAdmin@Category', $category ? array(
                'category' => $category
            ): array());
    }

    public function deleteAction(Context $context)
    {
        return $context->actionResponse('Action:CategoryDelete@Category');
    }
}