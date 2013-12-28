<?php

namespace Youngx\Bundle\AdminBundle\Module\VocabularyModule\Action;

use Youngx\Bundle\AdminBundle\Module\VocabularyModule\Entity\VocabularyEntity;
use Youngx\MVC\Action\DeleteAction;
use Youngx\MVC\Event\GetResponseEvent;

class VocabularyDeleteAction extends DeleteAction
{

    /**
     * @param VocabularyEntity[] $entities
     * @return string|void
     */
    protected function getMessageForEntities(array $entities)
    {
        $s = '您确定要删除词汇表';

        foreach ($entities as $entity) {
            $s .= ' <i>'.$entity->getLabel().'</i> ';
        }

        $s .= '吗？<strong>这将同时删除该词汇表下所有的术语！</strong>';

        return $s;
    }

    /**
     * @param VocabularyEntity[] $entities
     * @return string
     */
    protected function getSuccessMessage(array $entities)
    {
        $s = '成功删除词汇表';

        foreach ($entities as $entity) {
            $s .= ' <i>'.$entity->getLabel().'</i> ';
        }

        $s .= '！';

        return $s;
    }

    /**
     * @param VocabularyEntity[] $entities
     * @param GetResponseEvent $event
     */
    protected function delete(array $entities, GetResponseEvent $event)
    {
        foreach ($entities as $entity) {
            foreach ($entity->getTerms() as $term) {
                $term->delete();
            }
        }
        parent::delete($entities, $event);
    }

    /**
     * @return string
     */
    protected function entityType()
    {
        return 'vocabulary';
    }

    /**
     * @return string
     */
    protected function getCancelledUrl()
    {
        return $this->context->generateUrl('vocabulary-admin');
    }
}