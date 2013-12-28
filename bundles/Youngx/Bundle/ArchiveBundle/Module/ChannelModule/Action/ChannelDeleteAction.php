<?php

namespace Youngx\Bundle\ArchiveBundle\Module\ChannelModule\Action;

use Youngx\Bundle\ArchiveBundle\Module\ChannelModule\Entity\ChannelEntity;
use Youngx\MVC\Action\DeleteAction;
use Youngx\MVC\Event\GetResponseEvent;

class ChannelDeleteAction extends DeleteAction
{
    /**
     * @param ChannelEntity[] $entities
     * @return string
     */
    protected function getMessageForEntities(array $entities)
    {
        $s = '您确定要删除栏目';

        $ids = array();
        foreach ($entities as $entity) {
            $ids[] = '<i>  ' . $entity->getLabel() .'  </i>';
        }

        $s .= implode('，', $ids) . '吗？这将删除所有栏目下的子栏目！';

        return $s;
    }

    /**
     * @param ChannelEntity[] $entities
     * @param GetResponseEvent $event
     */
    protected function delete(array $entities, GetResponseEvent $event)
    {
        foreach ($entities as $entity) {
            $this->deleteChannel($entity);
        }
    }

    private function deleteChannel(ChannelEntity $category)
    {
        foreach ($category->getChildren() as $child) {
            $this->deleteChannel($child);
        }
        $category->delete();
    }

    /**
     * @param ChannelEntity[] $entities
     * @return string
     */
    protected function getSuccessMessage(array $entities)
    {
        $s = '成功删除栏目 ';

        $ids = array();
        foreach ($entities as $entity) {
            $ids[] = '<i>  ' . $entity->getLabel() .'  </i>';
        }

        $s .= implode('，', $ids) . ' 及其下所有子栏目！';

        return $s;
    }

    protected function entityType()
    {
        return 'channel';
    }

    /**
     * @return string
     */
    protected function getCancelledUrl()
    {
        return $this->context->generateUrl('channel-admin');
    }
}