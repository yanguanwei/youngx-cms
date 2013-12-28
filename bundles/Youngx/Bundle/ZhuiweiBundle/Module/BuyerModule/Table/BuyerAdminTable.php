<?php

namespace Youngx\Bundle\ZhuiweiBundle\Module\BuyerModule\Table;

use Youngx\Bundle\UserBundle\Entity\UserEntity;
use Youngx\Bundle\UserBundle\Table\UserAdminTable;
use Youngx\Bundle\ZhuiweiBundle\Module\BuyerModule\Entity\BuyerEntity;
use Youngx\Bundle\ZhuiweiBundle\Module\SellerModule\Entity\SellerVipEntity;
use Youngx\Database\Query;
use Youngx\EventHandler\Event\GetArrayEvent;
use Youngx\MVC\Html;
use Youngx\MVC\ListView\ColumnCollection;
use Youngx\MVC\RenderableResponse;

class BuyerAdminTable extends UserAdminTable
{
    protected function filter(Query $query, GetArrayEvent $event)
    {
        parent::filter($query, $event);
        $query->innerJoinTable(array('zw_buyer', 'buyer'), 'buyer.uid=y_user.uid');
    }

    protected function collectColumns(ColumnCollection $collection)
    {
        parent::collectColumns($collection);
        $collection->add('user_type', '类型');
    }

    protected function formatUserTypeColumn(UserEntity $entity, Html $html)
    {
        $buyer = $this->context->repository()->load('buyer', $entity->getUid());
        if ($buyer && $buyer instanceof BuyerEntity) {
            if ($buyer->getCompanyId()) {
                $html->setContent(sprintf(
                        '%s买家<br /><a href="%s">%s</a>',
                        $buyer->getType() == BuyerEntity::TYPE_COMPANY ? '机构' : '机构个人',
                        $this->context->generateUrl('company-admin-edit', array(
                                'company' => $buyer->getCompanyId(),
                                'returnUrl' => $this->context->request()->getUri()
                            )),
                        $buyer->getCompany()->getName()
                    ));
            } else {
                $html->setContent('个人买家');
            }
        }
    }

    protected function render(RenderableResponse $response)
    {
        parent::render($response);
        $response->addVariable('#subtitle', '买家列表');
    }
}