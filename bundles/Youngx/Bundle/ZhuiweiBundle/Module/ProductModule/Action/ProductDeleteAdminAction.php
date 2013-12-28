<?php

namespace Youngx\Bundle\ZhuiweiBundle\Module\ProductModule\Action;

use Youngx\Bundle\ZhuiweiBundle\Module\ProductModule\Entity\ProductEntity;
use Youngx\MVC\Action\DeleteAction;

class ProductDeleteAdminAction extends DeleteAction
{

    /**
     * @return string
     */
    protected function getCancelledUrl()
    {
        return $this->context->generateUrl('product-admin');
    }


    /**
     * @param ProductEntity[] $entities
     * @return string|void
     */
    protected function getSuccessMessage(array $entities)
    {
        $s = '删除产品 <i>';

        $labels = array();
        foreach ($entities as $entity) {
            $labels[] = $entity->getTitle();
        }
        $s .= implode('，', $labels) . '</i> 成功！';

        return $s;
    }

    /**
     * @param ProductEntity[] $entities
     * @return string
     */
    protected function getMessageForEntities(array $entities)
    {
        $s = '您确定要删除产品 <i>';

        $labels = array();
        foreach ($entities as $entity) {
            $labels[] = $entity->getTitle();
        }

        $s .= implode('，', $labels) . '</i> 吗？';

        return $s;
    }

    /**
     * @return string
     */
    protected function entityType()
    {
        return 'product';
    }
}