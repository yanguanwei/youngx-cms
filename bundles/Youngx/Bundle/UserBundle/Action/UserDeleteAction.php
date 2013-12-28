<?php

namespace Youngx\Bundle\UserBundle\Action;

use Youngx\Bundle\UserBundle\Entity\UserEntity;
use Youngx\MVC\Action\DeleteAction;

class UserDeleteAction extends DeleteAction
{

    /**
     * @param UserEntity[] $entities
     * @return string|void
     */
    protected function getMessageForEntities(array $entities)
    {
        $s = '您确定要删除用户 <i>';

        $labels = array();
        foreach ($entities as $entity) {
            $labels[] = $entity->getName();
        }
        $s .= implode('，', $labels) . '</i> 吗？';

        return $s;
    }

    /**
     * @param UserEntity[] $entities
     * @return string
     */
    protected function getSuccessMessage(array $entities)
    {
        $s = '成功删除用户 <i>';

        $labels = array();
        foreach ($entities as $entity) {
            $labels[] = $entity->getName();
        }

        $s .= implode('，', $labels) . '</i> ！';

        return $s;
    }

    /**
     * @return string
     */
    protected function getCancelledUrl()
    {
        return $this->context->generateUrl('user-admin');
    }

    /**
     * @return string
     */
    protected function entityType()
    {
        return 'user';
    }
}