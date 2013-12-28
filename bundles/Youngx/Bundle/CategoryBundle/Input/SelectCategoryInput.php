<?php

namespace Youngx\Bundle\CategoryBundle\Input;

use Youngx\Bundle\AdminBundle\Module\DistrictModule\Entity\DistrictEntity;
use Youngx\Bundle\CategoryBundle\Entity\CategoryEntity;
use Youngx\Bundle\jQueryBundle\Input\CXSelectInput;

class SelectCategoryInput extends CXSelectInput
{
    protected function init()
    {
        parent::init();

        $this->setUrl($this->context->generateUrl('category-ajax-cxselect'));;
        $this->setSelectsTotal(4);
    }

    public function setValue($value)
    {
        $current = $this->context->repository()->load('category', $value);
        if ($current && $current instanceof CategoryEntity) {
            $paths = $current->getPaths();
            $values = array();
            foreach ($paths as $i => $entity) {
                $values[$this->selects[$i]] = $paths[$i]->getId();
            }
            $this->setValues($values);
        }
    }
}