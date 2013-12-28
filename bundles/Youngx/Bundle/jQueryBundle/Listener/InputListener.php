<?php

namespace Youngx\Bundle\jQueryBundle\Listener;

use Youngx\Bundle\jQueryBundle\Input\ImageUploaderInput;
use Youngx\EventHandler\Registration;
use Youngx\MVC\Context;
use Youngx\MVC\Event\GetHtmlEvent;

class InputListener implements Registration
{
    /**
     * @var Context $context
     */
    protected $context;

    public function setContext(Context $context)
    {
        $this->context = $context;
    }

    public function datepicker(GetHtmlEvent $event, array $attributes = array())
    {
        if (!$event->hasHtml()) {
            $text = $this->context->input('text', $attributes);
            if ($text) {
                $event->setHtml($text);
                $this->context->assets()->registerPackage('jquery.ui.datepicker');
                if ($text->get('#datepicker') !== false) {
                    $options = $text->getData('datepicker');
                    $options = $options ? json_encode($options) : '';
                    $code = <<<code
$('#{$text->get('id')}').datepicker({$options});
code;

                    $this->context->assets()->registerScriptCode("datepicker#{$text->get('id')}", $code);
                }
            }
        }
    }

    public function imageUploaderInput(array $attributes)
    {
        return new ImageUploaderInput($this->context, $attributes);
    }

    public static function registerListeners()
    {
        return array(
            //'kernel.input#datepicker' => 'datepicker',
            'kernel.input#image-uploader' => 'imageUploaderInput'
        );
    }
}