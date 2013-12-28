<?php

namespace Youngx\Bundle\KernelBundle\Listener;

use Youngx\EventHandler\Registration;
use Youngx\MVC\Context;

class InputListener implements Registration
{
    /**
     * @var Context
     */
    protected $context;

    public function setContext(Context $context)
    {
        $this->context = $context;
    }

    public function file(array $attributes = array())
    {
        return $this->context->html('file', $attributes);
    }

    public function text(array $attributes = array())
    {
        return $this->context->html('text', $attributes);
    }

    public function password(array $attributes = array())
    {
        $attributes['type'] = 'password';
        return $this->context->input('text', $attributes);
    }

    public function hidden(array $attributes = array())
    {
        return $this->context->html('hidden', $attributes);
    }

    public function textarea(array $attributes = array())
    {
        return $this->context->html('textarea', $attributes);
    }

    public function checkbox(array $attributes = array())
    {
        return $this->context->html('checkbox', $attributes);
    }

    public function radio(array $attributes = array())
    {
        return $this->context->html('radio', $attributes);
    }

    public function select(array $attributes = array())
    {
        return $this->context->html('select', $attributes);
    }

    public function captcha(array $attributes)
    {
        if (isset($attributes['#captcha_id'])) {
            $captcha_id = $attributes['#captcha_id'];
            unset($attributes['#captcha_id']);
        } else {
            $captcha_id = null;
        }

        $text = $this->text($attributes);
        $img = $this->context->html('img', array(
                'src' => $url = $this->context->generateUrl('captcha', array('id' => $captcha_id)),
                'title' => '点击换一个'
            ), true);
        $text->after($img, 'img');

        $code = <<<code
$('#{$img->getId()}').click(function() {
    var url = '{$url}';
    $(this).attr('src', url + (url.indexOf('?') > 0 ? '&' : '?') + '_t=' + new Date().getTime());
});
code;
        $this->context->assets()->registerScriptCode($img->getId(), $code);

        return $text;
    }

    public static function registerListeners()
    {
        return array(
            'kernel.input#text' => 'text',
            'kernel.input#textarea' => 'textarea',
            'kernel.input#password' => 'password',
            'kernel.input#hidden' => 'hidden',
            'kernel.input#checkbox' => 'checkbox',
            'kernel.input#radio' => 'radio',
            'kernel.input#select' => 'select',
            'kernel.input#file' => 'file',
            'kernel.input#captcha' => 'captcha'
        );
    }
}