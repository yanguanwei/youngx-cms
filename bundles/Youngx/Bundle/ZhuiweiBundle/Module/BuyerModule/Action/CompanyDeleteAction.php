<?php

namespace Youngx\Bundle\ZhuiweiBundle\Module\BuyerModule\Action;

use Youngx\Bundle\ZhuiweiBundle\Module\BuyerModule\Entity\CompanyEntity;
use Youngx\MVC\Action\DeleteAction;
use Youngx\MVC\Event\GetResponseEvent;

class CompanyDeleteAction extends DeleteAction
{
    /**
     * @param CompanyEntity[] $entities
     * @return string | null
     */
    protected function validateForEntities(array $entities)
    {
        $labels = array();
        foreach ($entities as $entity) {
            if ($entity->getUid()) {
                $labels[] = $entity->getName();
            }
        }

        if ($labels) {
            return sprintf('不能删除公司 <i>%s</i>，因为其都已绑定用户！', implode('，', $labels));
        }
    }

    /**
     * @param CompanyEntity[] $entities
     * @return string
     */
    protected function getSuccessMessage(array $entities)
    {
        $s = '成功删除公司 <i>';

        $labels = array();
        foreach ($entities as $entity) {
            $labels[] = $entity->getName();
        }

        $s .= implode('，', $labels) . '</i> ！';

        return $s;
    }

    /**
     * @param CompanyEntity[] $entities
     * @return string
     */
    protected function getMessageForEntities(array $entities)
    {
        $s = '您确定要删除公司 <i>';

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
        return $this->context->generateUrl('company-admin');
    }

    /**
     * @return string
     */
    protected function entityType()
    {
        return 'company';
    }
}