<?php
namespace Youngx\Bundle\ArchiveBundle\Module\NewsModule\Action;

use Youngx\Bundle\ArchiveBundle\Module\NewsModule\Entity\NewsEntity;
use Youngx\MVC\Action\DeleteAction;
use Youngx\MVC\Event\GetResponseEvent;

class NewsDeleteAction extends DeleteAction
{
    /**
     * @param NewsEntity[] $entities
     * @return string
     */
    protected function getMessageForEntities(array $entities)
    {
        $s = '您确定要删除资讯';

        $ids = array();
        foreach ($entities as $entity) {
            $ids[] = '<i> #' . $entity->getId() .' </i>';
        }

        $s .= implode('，', $ids) . '吗？';

        return $s;
    }

    protected function entityType()
    {
        return 'news';
    }

    /**
     * @param NewsEntity[] $entities
     * @param GetResponseEvent $event
     * @return mixed|void
     */
    protected function delete(array $entities, GetResponseEvent $event)
    {
        foreach ($entities as $entity) {
            $entity->getArchive()->delete();
        }

        parent::submit($entities, $event);
    }

    /**
     * @return string
     */
    protected function getCancelledUrl()
    {
        return $this->context->generateUrl('news-admin');
    }
}