<?php

namespace Youngx\Bundle\AdminBundle\Module\DistrictModule\Listener;

use Youngx\Bundle\AdminBundle\Module\DistrictModule\Input\SelectDistrictInput;
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

    public function getDistrictPath($districtId)
    {
        $path = array();
        while ($districtId && null != ($district = $this->context->repository()->load('district', $districtId))) {
            array_unshift($path, $districtId);
            $districtId = $district->get('parent_id');
        }
        return $path;
    }

    public function selectDistrictInput(array $attributes)
    {
        return new SelectDistrictInput($this->context, $attributes);
    }

    public static function registerListeners()
    {
        return array(
            'kernel.value#district-path' => 'getDistrictPath',
            'kernel.input#select-district' => 'selectDistrictInput'
        );
    }
}