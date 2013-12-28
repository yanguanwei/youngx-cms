<?php

namespace Youngx\Bundle\ZhuiweiBundle\Module\ProductModule\Table;

use Youngx\Bundle\ZhuiweiBundle\Module\ProductModule\Entity\ProductEntity;
use Youngx\Database\Query;
use Youngx\EventHandler\Event\GetArrayEvent;
use Youngx\MVC\ListView\ColumnCollection;
use Youngx\MVC\Table;

class ProductPriceAdminTable extends Table
{
    /**
     * @var ProductEntity
     */
    protected $product;

    protected $type = 0;

    public function id()
    {
        return 'product-admin-price';
    }

    protected function initRequest()
    {
        if (!$this->product) {
            throw new \Exception('未指定产品');
        }
    }

    /**
     * @return Query
     */
    protected function query()
    {
        return $this->context->repository()->query('product_type');
    }

    protected function filter(Query $query, GetArrayEvent $event)
    {
        $query->where('product_id=:product_id AND type=:type');
        $event->addArray(array(
                ':product_id' => $this->product->getId(),
                ':type' => $this->type
            ));
    }

    protected function collectColumns(ColumnCollection $collection)
    {
        $collection->add('price', '价格');
        $collection->add('op', '运算符');
        $collection->add('quantity', '起订量');
    }

    /**
     * @param mixed $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param ProductEntity $product
     */
    public function setProduct(ProductEntity $product)
    {
        $this->product = $product;
    }

    /**
     * @return ProductEntity
     */
    public function getProduct()
    {
        return $this->product;
    }
}