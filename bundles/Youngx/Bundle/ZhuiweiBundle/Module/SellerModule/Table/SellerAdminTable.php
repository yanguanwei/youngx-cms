<?php

namespace Youngx\Bundle\ZhuiweiBundle\Module\SellerModule\Table;

use Youngx\Bundle\UserBundle\Entity\UserEntity;
use Youngx\Bundle\UserBundle\Table\UserAdminTable;
use Youngx\Bundle\ZhuiweiBundle\Module\SellerModule\Entity\SellerEntity;
use Youngx\Database\Query;
use Youngx\EventHandler\Event\GetArrayEvent;
use Youngx\MVC\Html;
use Youngx\MVC\ListView\ColumnCollection;
use Youngx\MVC\RenderableResponse;

class SellerAdminTable extends UserAdminTable
{
    protected $vipTypes;

    protected function filter(Query $query, GetArrayEvent $event)
    {
        $query->innerJoinTable(array('zw_seller', 'zws'), 'zws.uid=y_user.uid');
    }

    protected function collectColumns(ColumnCollection $collection)
    {
        parent::collectColumns($collection);
        $collection->add('vip_type', 'VIP类型');
    }

    protected function render(RenderableResponse $response)
    {
        $this->vipTypes = (array) $this->context->value('seller-vip-types');
        parent::render($response);
        $response->addVariable('#subtitle', '卖家列表');
    }

    protected function formatVipTypeColumn(UserEntity $entity, Html $html)
    {
        $seller = $this->context->repository()->load('seller', $entity->getUid());
        if ($seller instanceof SellerEntity) {
            if ($seller->getType() == SellerEntity::TYPE_WILLING) {
                $html->setContent('潜在客户');
            } else if ($seller->getType() > SellerEntity::TYPE_WILLING) {
                $html->setContent($this->vipTypes[$seller->getType()]);
            } else {
                $html->setContent($this->context->html('a', array(
                            '#content' => '有意向',
                            'href' => $this->context->generateUrl('seller-admin-vip-willing', array(
                                    'user' => $entity->getUid(),
                                    'returnUrl' => $this->context->request()->getUri()
                                ))
                        )));
            }
        }
    }

    protected function formatOperationsColumn(UserEntity $entity, Html $html)
    {
        parent::formatOperationsColumn($entity, $html);

        $returnUrl = $this->context->request()->getUri();
        $seller = $this->context->repository()->load('seller', $entity->getUid());

        $vip = $this->context->html('a', array(
                'href' => $this->context->generateUrl('seller-admin-edit', array(
                        'user' => $entity->getId(),
                        'returnUrl' => $returnUrl
                    )),
                '#content' => '查看'
            ));

        $factory = $this->context->html('a', array(
                'href' => $this->context->generateUrl('factory-admin-edit', array(
                        'factory' => $seller->get('factory_id'),
                        'returnUrl' => $returnUrl
                    )),
                '#content' => '工厂'
            ));

        $html->setContent("{$factory} | {$vip} | {$html->getContent()}");
    }
}