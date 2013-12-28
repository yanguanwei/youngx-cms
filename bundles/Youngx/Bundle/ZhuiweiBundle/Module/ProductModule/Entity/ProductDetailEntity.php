<?php

namespace Youngx\Bundle\ZhuiweiBundle\Module\ProductModule\Entity;

use Youngx\Database\Entity;

class ProductDetailEntity extends Entity
{
    protected $product_id;
    protected $introduction;
    protected $description;

    /**
     * @return ProductEntity
     */
    public function getProduct()
    {
        return $this->repository()->load('product', $this->product_id);
    }

    public static function type()
    {
        return 'product-detail';
    }

    public static function table()
    {
        return 'zw_product_detail';
    }

    public static function primaryKey()
    {
        return 'product_id';
    }

    public static function fields()
    {
        return array(
            'product_id', 'introduction', 'description'
        );
    }

    /**
     * @param mixed $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $product_id
     */
    public function setProductId($product_id)
    {
        $this->product_id = $product_id;
    }

    /**
     * @return mixed
     */
    public function getProductId()
    {
        return $this->product_id;
    }

    /**
     * @param mixed $Introduction
     */
    public function setIntroduction($Introduction)
    {
        $this->introduction = $Introduction;
    }

    /**
     * @return mixed
     */
    public function getIntroduction()
    {
        return $this->introduction;
    }
}