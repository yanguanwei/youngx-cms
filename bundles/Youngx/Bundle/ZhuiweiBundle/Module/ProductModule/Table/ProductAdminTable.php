<?php

namespace Youngx\Bundle\ZhuiweiBundle\Module\ProductModule\Table;

use Youngx\Bundle\ZhuiweiBundle\Module\ProductModule\Entity\ProductEntity;
use Youngx\Bundle\ZhuiweiBundle\Module\SellerModule\Entity\SellerEntity;
use Youngx\Database\Query;
use Youngx\EventHandler\Event\GetArrayEvent;
use Youngx\MVC\Html;
use Youngx\MVC\ListView\ColumnCollection;
use Youngx\MVC\RenderableResponse;
use Youngx\MVC\Table;
use Youngx\MVC\Widget\FormWidget;

class ProductAdminTable extends Table
{
    protected $title;
    protected $uid;

    public function id()
    {
        return 'product-admin';
    }

    /**
     * @return Query
     */
    protected function query()
    {
        $select = $this->context->db()->select(array('zw_index_product', 'zwip'), 'product_id');

        if ($this->uid) {
            $select->innerJoin(array('zw_index_product_user', 'zwipu'), 'zwipu.product_id=zwip.product_id')->where("zwipu.uid=?", $this->uid);
        }

        if ($this->title) {
            $title = $this->context->db()->quoteLike($this->title);
            $select->innerJoin(array('zw_index_product_title', 'zwipt'), 'zwipt.product_id=zwip.product_id')->where("zwipt.title LIKE '%{$title}%'");
        }

        $sql = (string) $select;
        $table = $this->context->db()->createTemporary("SELECT product_id FROM ({$sql}) t");

        $query = $this->context->repository()->query('product', 'product');

        if ($table) {
            $query->innerJoinTable(array($table, 'zwi'), 'zwi.product_id=product.id');
        }

        return $query;
    }

    protected function collectColumns(ColumnCollection $collection)
    {
        $collection->add('id', '产品ID');
        $collection->add('title', '产品名称');
        $collection->add('trade_price', '外贸价');
        $collection->add('taobao_price', '淘宝价');
        $collection->add('stock_price', '库存价');
        $collection->add('custom_price', '大客户价');
        $collection->add('user', '卖家');
        $collection->add('operations', '操作');
    }

    protected function render(RenderableResponse $response)
    {
        $searchForm = $this->context->widget('Form', array(
                '#skin' => 'search',
                '#action' => $this
            ));

        $response->setContent($searchForm . $this->context->widget('Table', array(
                    '#table' => $this
                )))->addVariable('#subtitle', '产品列表');
    }

    public function renderFormWidget(FormWidget $form)
    {
        $form->addField('title')->label('产品名称')->text();
        $form->addField('uid')->label('卖家帐号')->select_user();
    }

    protected function formatIdColumnHeading(Html $th)
    {
        $th->style('width', '6%')->addClass('center');
    }

    protected function formatTradePriceColumnHeading(Html $th)
    {
        $th->style('width', '6%')->addClass('center');
    }

    protected function formatTaobaoPriceColumnHeading(Html $th)
    {
        $th->style('width', '6%')->addClass('center');
    }

    protected function formatStockPriceColumnHeading(Html $th)
    {
        $th->style('width', '6%')->addClass('center');
    }

    protected function formatCustomPriceColumnHeading(Html $th)
    {
        $th->style('width', '6%')->addClass('center');
    }

    protected function formatIdColumnHtml(Html $th)
    {
        $th->addClass('center');
    }

    protected function formatTitleColumn(ProductEntity $entity, Html $td)
    {
        $td->setContent(sprintf('%s<br />%s', $entity->getTitle(), $entity->getCode()));
    }

    protected function formatTradePriceColumn(ProductEntity $entity, Html $td)
    {
        $trade = $entity->getTradePrice();
        if ($trade) {
            $term = $entity->getUnit();
            $td->setContent(sprintf('%0.2f￥<br />%d%s', $trade->getPrice(), $trade->getQuantity(), $term ? $term->getLabel() : ''));
        }
    }

    protected function formatTaobaoPriceColumn(ProductEntity $entity, Html $td)
    {
        $price = $entity->getTaobaoPrice();
        if ($price) {
            $term = $entity->getUnit();
            $td->setContent(sprintf('%0.2f￥<br />%d%s', $price->getPrice(), $price->getQuantity(), $term ? $term->getLabel() : ''));
        }
    }

    protected function formatStockPriceColumn(ProductEntity $entity, Html $td)
    {
        $price = $entity->getStockPrice();
        if ($price) {
            $term = $entity->getUnit();
            $td->setContent(sprintf('%0.2f￥<br />%d%s', $price->getPrice(), $price->getQuantity(), $term ? $term->getLabel() : ''));
        }
    }

    protected function formatCustomPriceColumn(ProductEntity $entity, Html $td)
    {
        $price = $entity->getCustomerPrice();
        if ($price) {
            $term = $entity->getUnit();
            $td->setContent(sprintf('%0.2f￥<br />%d%s', $price->getPrice(), $price->getQuantity(), $term ? $term->getLabel() : ''));
        }
    }

    protected function formatUserColumn(ProductEntity $entity, Html $td)
    {
        $user = $entity->getUser();
        $returnUrl = $this->context->request()->getUri();

        if ($user) {
            $content = $this->context->html('a', array(
                    '#content' => $user->getName(),
                    'href' => $this->context->generateUrl('user-admin-account', array('user' => $user->getUid(), 'returnUrl' => $returnUrl))
                ));

            $seller = $this->context->repository()->load('seller', $user->getUid());
            if ($seller && $seller instanceof SellerEntity) {
                $factory = $seller->getFactory();
                if ($factory) {
                    $content .= '<br />' . $this->context->html('a', array(
                                '#content' => $factory->getName(),
                                'href' => $this->context->generateUrl('factory-admin-edit', array(
                                        'factory' => $factory->getId(),
                                        'returnUrl' => $returnUrl
                                    ))
                            ));
                }
            }

            $td->setContent($content);
        }
    }

    protected function formatOperationsColumn(ProductEntity $entity, Html $td)
    {
        $edit = $this->context->html('a', array(
                'href' => $this->context->generateUrl('product-admin-edit', array(
                        'product' => $entity->getId(),
                        'returnUrl' => $this->context->request()->getUri()
                    )),
                '#content' => '编辑'
            ));

        $delete = $this->context->html(
            'a', array(
                'href' => $this->context->generateUrl('product-admin-delete', array(
                        'id' => $entity->getId(),
                        'returnUrl' => $this->context->request()->getUri()
                    )),
                '#content' => '删除'
            )
        );

        $td->setContent("{$edit} | {$delete}");
    }

    /**
     * @param mixed $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param mixed $uid
     */
    public function setUid($uid)
    {
        $this->uid = intval($uid);
    }

    /**
     * @return mixed
     */
    public function getUid()
    {
        return $this->uid;
    }
}