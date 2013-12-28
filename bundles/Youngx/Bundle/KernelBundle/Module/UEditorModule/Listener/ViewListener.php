<?php

namespace Youngx\Bundle\KernelBundle\Module\UEditorModule\Listener;

use Youngx\EventHandler\Registration;
use Youngx\MVC\Assets;
use Youngx\MVC\Context;
use Youngx\MVC\Html;
use Youngx\MVC\User\Identity;

class ViewListener implements Registration
{
    /**
     * @var Context
     */
    private $context;

    public function setContext(Context $context)
    {
        $this->context = $context;
    }

    public function umeditorPackage(Assets $assets)
    {
        $assets->registerScriptUrl('umeditor-config', 'UEditor_mini/umeditor.config.js');
        $assets->registerScriptUrl('umeditor', 'UEditor_mini/umeditor.js');
        $assets->registerStyleUrl('umeditor-default', 'UEditor_mini/themes/default/css/umeditor.min.css');
    }

    public function umeditorInput(array $attributes)
    {
        $textarea = $this->context->input('textarea', $attributes);
        $this->context->assets()->registerPackage('umeditor');

        $code = <<<code
var ue = UM.getEditor('{$textarea->getId()}');
code;
        $this->context->assets()->registerScriptCode("{$textarea->getId()}.umeditor", $code);

        return $textarea;
    }

    public static function registerListeners()
    {
        return array(
            'kernel.assets.package#umeditor' => 'umeditorPackage',
            'kernel.input#umeditor' => 'umeditorInput',
        );
    }
}