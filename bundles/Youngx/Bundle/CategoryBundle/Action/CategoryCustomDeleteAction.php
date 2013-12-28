<?php

namespace Youngx\Bundle\CategoryBundle\Action;

use Youngx\Bundle\CategoryBundle\Entity\CategoryCustomEntity;
use Youngx\MVC\Action\DeleteAction;

class CategoryCustomDeleteAction extends DeleteAction
{
    /**
     * @param CategoryCustomEntity[] $entities
     * @return string|void
     */
    protected function getMessageForEntities(array $entities)
    {
        $s = '您确定要删除自定义分类 <i>';

        $labels = array();
        foreach ($entities as $entity) {
            $labels[] = $entity->getLabel();
        }
        $s .= implode('，', $labels) . '</i> 吗？';

        return $s;
    }

    /**
     * @param CategoryCustomEntity[] $entities
     * @return string
     */
    protected function getSuccessMessage(array $entities)
    {
        $s = '成功删除自定义分类 <i>';

        $labels = array();
        foreach ($entities as $entity) {
            $labels[] = $entity->getLabel();
        }

        $s .= implode('，', $labels) . '</i> ！';

        return $s;
    }

    /**
     * @return string
     */
    protected function entityType()
    {
        return 'category_custom';
    }

    /**
     * @return string
     */
    protected function getCancelledUrl()
    {
        return $this->context->generateUrl('category-admin');
    }
}