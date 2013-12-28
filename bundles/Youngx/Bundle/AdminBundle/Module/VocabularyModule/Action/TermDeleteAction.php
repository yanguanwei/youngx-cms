<?php

namespace Youngx\Bundle\AdminBundle\Module\VocabularyModule\Action;

use Youngx\Bundle\AdminBundle\Module\VocabularyModule\Entity\TermEntity;
use Youngx\MVC\Action\DeleteAction;

class TermDeleteAction extends DeleteAction
{

    /**
     * @param TermEntity[] $entities
     * @return string|void
     */
    protected function getMessageForEntities(array $entities)
    {
        $s = '您确定要删除术语';

        foreach ($entities as $entity) {
            $s .= ' <i>'.$entity->getLabel().'</i> ';
        }

        $s .= '吗？';

        return $s;
    }

    /**
     * @param TermEntity[] $entities
     * @return string
     */
    protected function getSuccessMessage(array $entities)
    {
        $s = '成功删除术语';

        foreach ($entities as $entity) {
            $s .= ' <i>'.$entity->getLabel().'</i> ';
        }

        $s .= '！';

        return $s;
    }

    /**
     * @return string
     */
    protected function entityType()
    {
        return 'term';
    }

    /**
     * @return string
     */
    protected function getCancelledUrl()
    {
        return $this->context->generateUrl('vocabulary-admin');
    }
}