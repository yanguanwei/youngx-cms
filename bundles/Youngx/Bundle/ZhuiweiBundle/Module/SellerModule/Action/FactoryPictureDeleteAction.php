<?php

namespace Youngx\Bundle\ZhuiweiBundle\Module\SellerModule\Action;

use Youngx\Bundle\AdminBundle\Module\FileModule\Action\FileDeleteAction;
use Youngx\Bundle\AdminBundle\Module\FileModule\Entity\FileEntity;
use Youngx\Bundle\ZhuiweiBundle\Module\SellerModule\Entity\FactoryEntity;
use Youngx\MVC\Event\GetResponseEvent;
use Youngx\MVC\Exception\HttpException;

class FactoryPictureDeleteAction extends FileDeleteAction
{
    /**
     * @var FactoryEntity
     */
    protected $factory;

    protected $picture;

    protected function initRequest()
    {
        $picture = intval($this->context->request()->get('picture'));

        if (1 > $picture || $picture > 5) {
            throw new HttpException(404);
        }

        $this->picture = $picture;
    }

    /**
     * @param FileEntity[] $entities
     * @return string
     */
    protected function getMessageForEntities(array $entities)
    {
        return sprintf('您确定要删除工厂 <i>%s</i> 的<i>厂房图片%s</i> 吗？', $this->factory->getName(), $this->picture);
    }

    /**
     * @param FileEntity[] $entities
     * @return string
     */
    protected function getSuccessMessage(array $entities)
    {
        return sprintf('成功删除工厂 <i>%s</i> 的<i>厂房图片%s</i> ！', $this->factory->getName(), $this->picture);
    }

    /**
     * @param array $entities
     * @param GetResponseEvent $event
     */
    protected function delete(array $entities, GetResponseEvent $event)
    {
        $this->factory->set("picture{$this->picture}", 0);
        parent::delete($entities, $event);
    }

    /**
     * @param FactoryEntity $factory
     */
    public function setFactory(FactoryEntity $factory)
    {
        $this->factory = $factory;
    }

    /**
     * @return FactoryEntity
     */
    public function getFactory()
    {
        return $this->factory;
    }
}