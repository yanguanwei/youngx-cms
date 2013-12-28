<?php

namespace Youngx\Bundle\CategoryBundle\Action;

use Youngx\Bundle\CategoryBundle\Entity\CategoryEntity;
use Youngx\MVC\Action\DeleteAction;
use Youngx\MVC\Event\GetResponseEvent;

class CategoryDeleteAction extends DeleteAction
{
    /**
     * @param CategoryEntity[] $entities
     * @return string|void
     */
    protected function getMessageForEntities(array $entities)
    {
        $s = '您确定要删除分类 <i>';

        $labels = array();
        foreach ($entities as $entity) {
            $labels[] = $entity->getLabel();
        }
        $s .= implode('，', $labels) . '</i> 吗？<strong>这将同时删除该分类下的所有子分类！</strong>';

        return $s;
    }

    /**
     * @param CategoryEntity[] $entities
     * @return string
     */
    protected function getSuccessMessage(array $entities)
    {
        $s = '成功删除分类 <i>';

        $labels = array();
        foreach ($entities as $entity) {
            $labels[] = $entity->getLabel();
        }
        $s .= implode('，', $labels) . '</i> 及其下所有的子分类！';

        return $s;
    }

    /**
     * @param CategoryEntity[] $entities
     * @param GetResponseEvent $event
     */
    protected function delete(array $entities, GetResponseEvent $event)
    {
        foreach ($entities as $entity) {
            $this->deleteCategory($entity);
        }
    }

    private function deleteCategory(CategoryEntity $category)
    {
        foreach ($category->getChildren() as $child) {
            $this->deleteCategory($child);
        }
        $category->delete();
    }

    /**
     * @return string
     */
    protected function entityType()
    {
        return 'category';
    }

    /**
     * @return string
     */
    protected function getCancelledUrl()
    {
        return $this->context->generateUrl('category-list');
    }
}