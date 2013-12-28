<?php

namespace Youngx\Bundle\CategoryBundle\Listener;

use Youngx\Bundle\CategoryBundle\Input\SelectCategoryInput;
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

    public function selectCategory(array $attributes)
    {
        $options = $hierarchy = array();
        $sql = "SELECT id, parent_id, label FROM y_category ORDER BY sort_num ASC, id ASC";
        foreach ($this->context->db()->query($sql)->fetchAll() as $category) {
            $hierarchy[$category['id']] = $category['parent_id'];
            $options[$category['id']] = $category['label'];
        }
        $attributes['#options'] = $options;
        $attributes['#hierarchy'] = $hierarchy;

        return $this->context->input('select', $attributes);
    }

    public function cxselectCategoryInput(array $attributes)
    {
        return new SelectCategoryInput($this->context, $attributes);
    }

    public static function registerListeners()
    {
        return array(
            'kernel.input#select-category' => 'selectCategory',
            'kernel.input#cxselect-category' => 'cxselectCategoryInput',
        );
    }
}