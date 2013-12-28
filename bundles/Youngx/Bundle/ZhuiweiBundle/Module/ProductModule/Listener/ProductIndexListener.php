<?php

namespace Youngx\Bundle\ZhuiweiBundle\Module\ProductModule\Listener;

use Youngx\Bundle\AdminBundle\Module\DistrictModule\Entity\DistrictEntity;
use Youngx\Bundle\ZhuiweiBundle\Module\ProductModule\Entity\ProductEntity;
use Youngx\Bundle\ZhuiweiBundle\Module\ProductModule\Entity\ProductPriceEntity;
use Youngx\Bundle\ZhuiweiBundle\Module\SellerModule\Entity\FactoryEntity;
use Youngx\Bundle\ZhuiweiBundle\Module\SellerModule\Entity\SellerEntity;
use Youngx\EventHandler\Registration;
use Youngx\MVC\Context;

class ProductIndexListener implements Registration
{

    /**
     * @var Context
     */
    protected $context;

    public function setContext(Context $context)
    {
        $this->context = $context;
    }

    public function deleteProductEntity(ProductEntity $entity)
    {
        $db = $this->context->db();
        $productId = $entity->getId();

        $db->delete('zw_index_product', "product_id='{$productId}'");
        $db->delete('zw_index_product_status', "product_id='{$productId}'");
        $db->delete('zw_index_product_price_stock', "product_id='{$productId}'");
        $db->delete('zw_index_product_category', "product_id='{$productId}'");
        $db->delete('zw_index_product_district', "product_id='{$productId}'");
        $db->delete('zw_index_product_user', "product_id='{$productId}'");
        $db->delete('zw_index_product_title', "product_id='{$productId}'");
        $db->delete('zw_index_product_created', "product_id='{$productId}'");
    }

    public function deleteProductPrice(ProductPriceEntity $entity)
    {
        if ($entity->getType() == ProductPriceEntity::TYPE_STOCK) {
            $productId = $entity->getProductId();
            $db = $this->context->db();
            $db->delete('zw_index_product_price_stock', "product_id='{$productId}'");
        }
    }

    public function updateProductPriceIndex(ProductPriceEntity $entity)
    {
        if ($entity->getType() == ProductPriceEntity::TYPE_STOCK) {
            $productId = $entity->getProductId();
            $db = $this->context->db();
            $db->delete('zw_index_product_price_stock', "product_id='{$productId}'");
            $db->insert('zw_index_product_price_stock', array(
                    'product_id' => $productId,
                    'price' => $entity->getPrice()
                ));
        }
    }

    protected function updateProductCreatedIndex(ProductEntity $entity)
    {
        $productId = $entity->getId();
        $db = $this->context->db();
        //$db->delete('zw_index_product_created', "product_id='{$productId}'");
        $db->insert('zw_index_product_created', array(
                'product_id' => $productId,
                'created' => $entity->getCreatedAt()
            ));
    }

    protected function updateProductStatusIndex(ProductEntity $entity)
    {
        $productId = $entity->getId();
        $db = $this->context->db();
        $db->delete('zw_index_product_status', "product_id='{$productId}'");
        $db->insert('zw_index_product_status', array(
                'product_id' => $productId,
                'status' => $entity->getStatus()
            ));
    }

    protected function updateProductCategoryIndex(ProductEntity $entity)
    {
        $productId = $entity->getId();
        $db = $this->context->db();

        $db->delete('zw_index_product_category', "product_id='{$productId}'");
        $category = $entity->getCategory();
        if ($category) {
            $indexData = array();
            foreach ($category->getPaths() as $cate) {
                $indexData[] = array(
                    'product_id' => $productId,
                    'category_id' => $cate->getId()
                );
            }
            if ($indexData) {
                $db->insertMultiple('zw_index_product_category', $indexData);
            }
        }
    }

    protected function updateProductUserIndex(ProductEntity $entity)
    {
        $productId = $entity->getId();
        $db = $this->context->db();

        $db->delete('zw_index_product_district', "product_id='{$productId}'");
        $db->delete('zw_index_product_user', "product_id='{$productId}'");
        $uid = $entity->getUid();
        if ($uid) {
            $db->insert('zw_index_product_user', array(
                    'product_id' => $productId,
                    'uid' => $uid
                ));
            $seller = $this->context->repository()->load('seller', $uid);
            if ($seller && $seller instanceof SellerEntity) {
                $factory = $seller->getFactory();
                if ($factory && $factory instanceof FactoryEntity) {
                    $district = $factory->getDistrict();
                    if ($district && $district instanceof DistrictEntity) {
                        $indexData = array();
                        foreach ($district->getPaths() as $dist) {
                            $indexData[] = array(
                                'product_id' => $productId,
                                'district_id' => $dist->getId()
                            );
                        }
                        if ($indexData) {
                            $db->insertMultiple('zw_index_product_district', $indexData);
                        }
                    }
                }
            }
        }
    }

    protected function updateProductTitleIndex(ProductEntity $entity)
    {
        $productId = $entity->getId();
        $db = $this->context->db();

        $db->delete('zw_index_product_title', "product_id='{$productId}'");
        if ($entity->getTitle()) {
            $db->insert('zw_index_product_title', array(
                    'product_id' => $productId,
                    'title' => $entity->getTitle()
                ));
        }
    }

    public function updateProductIndex(ProductEntity $entity)
    {
        $unchanged = $entity->unchangedEntity();

        if ($unchanged && $unchanged instanceof ProductEntity) {

            if ($unchanged->getCategoryId() != $entity->getCategoryId()) {
                $this->updateProductCategoryIndex($entity);
            }

            if ($unchanged->getUid() != $entity->getUid()) {
                $this->updateProductUserIndex($entity);
            }

            if ($unchanged->getTitle() != $entity->getTitle()) {
                $this->updateProductTitleIndex($entity);
            }

            if ($unchanged->getStatus() != $entity->getStatus()) {
                $this->updateProductStatusIndex($entity);
            }
        }
    }

    public function insertProductIndex(ProductEntity $entity)
    {
        $this->context->db()->insert('zw_index_product', array(
                'product_id' => $entity->getId()
            ));

        if ($entity->getCategoryId()) {
            $this->updateProductCategoryIndex($entity);
        }

        if ($entity->getUid()) {
            $this->updateProductUserIndex($entity);
        }

        if ($entity->getTitle()) {
            $this->updateProductTitleIndex($entity);
        }

        $this->updateProductStatusIndex($entity);

        $this->updateProductCreatedIndex($entity);
    }

    public static function registerListeners()
    {
        return array(
            'kernel.entity.delete#product' => 'deleteProductEntity',
            'kernel.entity.insert#product' => 'insertProductIndex',
            'kernel.entity.beforeUpdate#product' => 'updateProductIndex',
            'kernel.entity.save#product-price' => 'updateProductPriceIndex',
            'kernel.entity.delete#product-price' => 'deleteProductPrice',
        );
    }
}