<?php

namespace Youngx\Bundle\ZhuiweiBundle\Module\ProductModule\Entity;

use Youngx\Database\Entity;

class ProductPriceEntity extends Entity
{
    const OP_EQUAL = 0;
    const OP_LESS = 1;
    const OP_LESS_EQUAL = 2;
    const OP_GREATER = 3;
    const OP_GREATER_EQUAL = 4;

    const TYPE_TRADE = 0;
    const TYPE_TAOBAO = 1;
    const TYPE_STOCK = 2;
    const TYPE_CUSTOMER = 3;

    protected $id;
    protected $product_id = 0;
    protected $type = 0;
    protected $quantity = 1;
    protected $price = 0;
    protected $op = 0;

    /**
     * @return ProductEntity
     */
    public function getProduct()
    {
        return $this->repository()->load('product', $this->product_id);
    }

    public static function type()
    {
        return 'product-price';
    }

    public static function table()
    {
        return 'zw_product_price';
    }

    public static function primaryKey()
    {
        return 'id';
    }

    public static function fields()
    {
        return array(
            'id', 'type', 'product_id', 'quantity', 'price', 'op'
        );
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    public function setOp($op)
    {
        $this->op = $op;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getOp()
    {
        return $this->op;
    }

    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPrice()
    {
        return $this->price;
    }

    public function setProductId($product_id)
    {
        $this->product_id = $product_id;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getProductId()
    {
        return $this->product_id;
    }

    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }
}