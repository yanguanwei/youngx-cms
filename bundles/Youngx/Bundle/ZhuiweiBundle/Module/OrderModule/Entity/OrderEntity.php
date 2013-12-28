<?php

namespace Youngx\Bundle\ZhuiweiBundle\Module\OrderModule\Entity;

use Youngx\Bundle\UserBundle\Entity\UserEntity;
use Youngx\Bundle\ZhuiweiBundle\Module\ProductModule\Entity\ProductEntity;
use Youngx\Database\Entity;

class OrderEntity extends Entity
{
    /**
     * @var 未付款
     */
    const STATUS_UNPAID = 0;
    /**
     * @var 已发货
     */
    const STATUS_SHIPPED = 1;

    /**
     * @var 已接收
     */
    const STATUS_RECEIVED = 2;
    /**
     * @var 已验货
     */
    const STATUS_INSPECTED = 3;

    /**
     * @VAR 已退货
     */
    const STATUS_RETURNED = 4;

    /**
     * @var 已付款
     */
    const STATUS_PAID = 5;

    /**
     * @var 已完成
     */
    const STATUS_COMPLETED = 6;

    protected $id;
    protected $seller_uid;
    protected $buyer_uid;
    protected $product_id;
    protected $quantity;
    protected $price;
    protected $status;
    protected $shipping;
    protected $created_at;

    /**
     * @return OrderDeliveryEntity
     */
    public function getDelivery()
    {
        return $this->repository()->load('order_delivery', $this->id);
    }

    /**
     * @return UserEntity
     */
    public function getBuyer()
    {
        return $this->repository()->load('user', $this->buyer_uid);
    }

    /**
     * @return UserEntity
     */
    public function getSeller()
    {
        return $this->repository()->load('user', $this->seller_uid);
    }

    /**
     * @return ProductEntity
     */
    public function getProduct()
    {
        return $this->repository()->load('product', $this->product_id);
    }

    public static function type()
    {
        return 'order';
    }

    public static function table()
    {
        return 'zw_order';
    }

    public static function primaryKey()
    {
        return 'id';
    }

    public static function fields()
    {
        return array(
            'id', 'seller_uid', 'buyer_uid', 'product_id', 'quantity', 'price', 'status', 'shipping', 'created_at'
        );
    }

    /**
     * @param mixed $buyer_uid
     */
    public function setBuyerUid($buyer_uid)
    {
        $this->buyer_uid = $buyer_uid;
    }

    /**
     * @return mixed
     */
    public function getBuyerUid()
    {
        return $this->buyer_uid;
    }

    /**
     * @param mixed $created_at
     */
    public function setCreatedAt($created_at)
    {
        $this->created_at = $created_at;
    }

    /**
     * @return mixed
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $price
     */
    public function setPrice($price)
    {
        $this->price = $price;
    }

    /**
     * @return mixed
     */
    public function getPrice()
    {
        return $this->price;
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
     * @param mixed $quantity
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;
    }

    /**
     * @return mixed
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * @param mixed $seller_uid
     */
    public function setSellerUid($seller_uid)
    {
        $this->seller_uid = $seller_uid;
    }

    /**
     * @return mixed
     */
    public function getSellerUid()
    {
        return $this->seller_uid;
    }

    /**
     * @param mixed $shipping
     */
    public function setShipping($shipping)
    {
        $this->shipping = $shipping;
    }

    /**
     * @return mixed
     */
    public function getShipping()
    {
        return $this->shipping;
    }

    /**
     * @param mixed $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }
}