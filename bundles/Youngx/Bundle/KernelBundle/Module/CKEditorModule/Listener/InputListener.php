<?php

namespace Youngx\Bundle\KernelBundle\Module\CKEditorModule\Listener;

use Youngx\EventHandler\Registration;
use Youngx\MVC\Assets;
use Youngx\MVC\Context;
use Youngx\MVC\Event\GetHtmlEvent;

class InputListener implements Registration
{
    /**
     * @var Context
     */
    private $context;

    public function setContext(Context $context)
    {
        $this->context = $context;
    }

    public function ckeditorPackage(Assets $assets)
    {
        $assets->registerScriptUrl('ckeditor', 'CKEditor/ckeditor.js');
    }

    public function ckeditorFullPackage(Assets $assets)
    {
        $assets->registerScriptUrl('ckeditor', 'CKEditor_full/ckeditor.js');
    }

    public function ckeditorInput(array $attributes)
    {
        $textarea = $this->context->input('textarea', $attributes);
        $this->context->assets()->registerPackage('ckeditor');
        $this->context->assets()->registerScriptCode("{$textarea->getId()}.ckeditor", "var ckeditor = CKEDITOR.replace('{$textarea->getId()}');");

        return $textarea;
    }

    public function ckeditorFullInput(array $attributes)
    {
        $textarea = $this->context->input('textarea', $attributes);
        $this->context->assets()->registerPackage('ckeditor.full');
        $this->context->assets()->registerScriptCode("{$textarea->getId()}.ckeditor", "var ckeditor = CKEDITOR.replace('{$textarea->getId()}');");

        return $textarea;
    }

    public static function registerListeners()
    {
        return array(
            'kernel.input#ckeditor' => 'ckeditorInput',
            'kernel.input#ckeditor-full' => 'ckeditorFullInput',
            'kernel.assets.package#ckeditor' => 'ckeditorPackage',
            'kernel.assets.package#ckeditor.full' => 'ckeditorFullPackage'
        );
    }
}