<?php

namespace Youngx\Bundle\AdminBundle\Module\FileModule\Action;

use Youngx\Bundle\AdminBundle\Module\FileModule\Entity\FileEntity;
use Youngx\MVC\Action\DeleteAction;
use Youngx\Util\Directory;

class FileDeleteAction extends DeleteAction
{
    /**
     * @param FileEntity[] $entities
     * @return string|void
     */
    protected function getSuccessMessage(array $entities)
    {
        $s = '删除文件 <i>';

        $labels = array();
        foreach ($entities as $entity) {
            $labels[] = $entity->getFilename();
        }
        $s .= implode('，', $labels) . '</i> 成功！';

        return $s;
    }

    /**
     * @param FileEntity[] $entities
     * @return string
     */
    protected function getMessageForEntities(array $entities)
    {
        $s = '您确定要删除文件 <i>';

        $labels = array();
        foreach ($entities as $entity) {
            $labels[] = $entity->getFilename();
        }

        $s .= implode('，', $labels) . '</i> 吗？';

        return $s;
    }

    /**
     * @return string
     */
    protected function getCancelledUrl()
    {
        return $this->context->generateUrl('file-admin');
    }

    /**
     * @return string
     */
    protected function entityType()
    {
        return 'file';
    }
}