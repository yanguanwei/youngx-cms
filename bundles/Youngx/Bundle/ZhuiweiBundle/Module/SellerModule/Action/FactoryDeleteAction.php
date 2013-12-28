<?php

namespace Youngx\Bundle\ZhuiweiBundle\Module\SellerModule\Action;

use Youngx\Bundle\ZhuiweiBundle\Module\SellerModule\Entity\FactoryEntity;
use Youngx\MVC\Action\DeleteAction;
use Youngx\MVC\Event\GetResponseEvent;

class FactoryDeleteAction extends DeleteAction
{

    /**
     * @param FactoryEntity[] $entities
     * @return string
     */
    protected function getSuccessMessage(array $entities)
    {
        $s = '成功删除工厂 <i>';

        $labels = array();
        foreach ($entities as $entity) {
            $labels[] = $entity->getName();
        }

        $s .= implode('，', $labels) . '</i> ！';

        return $s;
    }

    /**
     * @param FactoryEntity[] $entities
     * @return string
     */
    protected function getMessageForEntities(array $entities)
    {
        $s = '您确定要删除工厂 <i>';

        $labels = array();
        foreach ($entities as $entity) {
            $labels[] = $entity->getName();
        }

        $s .= implode('，', $labels) . '</i> 吗？';

        return $s;
    }

    /**
     * @return string
     */
    protected function getCancelledUrl()
    {
        return $this->context->generateUrl('factory-admin');
    }

    /**
     * @return string
     */
    protected function entityType()
    {
        return 'factory';
    }
}