<?php

namespace Youngx\Bundle\ZhuiweiBundle\Module\ProductModule\Listener;

use Youngx\Bundle\AdminBundle\Module\FileModule\Entity\FileEntity;
use Youngx\Bundle\ZhuiweiBundle\Module\ProductModule\Entity\ProductEntity;
use Youngx\Bundle\ZhuiweiBundle\Module\ProductModule\Entity\ProductPriceEntity;
use Youngx\Database\Query;
use Youngx\EventHandler\Registration;
use Youngx\MVC\Context;

class MainListener implements Registration
{
    /**
     * @var Context
     */
    protected $context;

    public function setContext(Context $context)
    {
        $this->context = $context;
    }

    public function productPriceOpInput(array $attributes = array())
    {
        $attributes['#options'] = array(
            ProductPriceEntity::OP_EQUAL => '= ',
            ProductPriceEntity::OP_LESS => '< ',
            ProductPriceEntity::OP_LESS_EQUAL => '<=',
            ProductPriceEntity::OP_GREATER => '> ',
            ProductPriceEntity::OP_GREATER_EQUAL => '>='
        );

        $select = $this->context->input('select', $attributes);

        return $select;
    }

    public function deleteProductEntity(ProductEntity $entity)
    {
        $files = $this->context->value('files', $entity);
        if ($files) {
            foreach ($files as $file) {
                if ($file instanceof FileEntity) {
                    $file->delete();
                }
            }
        }

        $detail = $entity->getDetail();
        if ($detail) {
            $detail->delete();
        }

        foreach ($entity->getPrices() as $price) {
            $price->delete();
        }

    }

    public function selectProductStatusInput(array $attributes)
    {
        $attributes['#options'] = array(
            ProductEntity::STATUS_ON => '出售中',
            ProductEntity::STATUS_OFF => '下架中'
        );

        $select = $this->context->input('select', $attributes);

        return $select;
    }

    public static function registerListeners()
    {
        return array(
            'kernel.input#product-price-op' => 'productPriceOpInput',
            'kernel.entity.delete#product' => 'deleteProductEntity',
            'kernel.input#select-product-status' => 'selectProductStatusInput'
        );
    }
}