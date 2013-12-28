<?php

namespace Youngx\Bundle\KernelBundle\Listener;

use Youngx\EventHandler\Registration;
use Youngx\MVC\Html\SelectHtml;
use Youngx\MVC\Html\CheckboxHtml;
use Youngx\MVC\Html\RadioHtml;
use Youngx\MVC\Html;
use Youngx\MVC\Html\FormHtml;
use Youngx\MVC\Html\TextHtml;
use Youngx\MVC\Html\TextareaHtml;
use Youngx\MVC\Context;

class HtmlListener implements Registration
{
    /**
     * @var Context
     */
    protected $context;

    public function setContext(Context $context)
    {
        $this->context = $context;
    }

    public function html(array $attributes, $html, $empty)
    {
        return new Html($this->context, $html, $attributes, null, $empty);
    }

    public function button(array $attributes)
    {
        $attributes['type'] = 'button';
        return new Html($this->context, 'button', $attributes);
    }

    public function submit(array $attributes)
    {
        $attributes = array_merge($attributes, array(
                'type' => 'submit',
                'value' => '提交'
            ));
        return new Html($this->context, 'button', $attributes);
    }

    public function cancel(array $attributes)
    {
        if (null != ($returnUrl = $this->context->request()->get('returnUrl'))) {
            $attributes['href'] = $returnUrl;
        }
        if (!isset($attributes['#content'])) {
            $attributes['#content'] = '取消';
        }
        return new Html($this->context, 'a', $attributes, 'cancel');
    }

    public function checkbox(array $attributes)
    {
        $attributes['type'] = 'checkbox';
        return new CheckboxHtml($this->context, $attributes);
    }

    public function hidden(array $attributes)
    {
        $attributes['type'] = 'hidden';
        return new TextHtml($this->context, $attributes, 'hidden');
    }

    public function message(array $attributes)
    {
        return new Html\MessageHtml($this->context, $attributes);
    }

    public function radio(array $attributes)
    {
        return new RadioHtml($this->context, $attributes);
    }

    public function select(array $attributes)
    {
        return new SelectHtml($this->context, $attributes);
    }

    public function form(array $attributes)
    {
        return new FormHtml($this->context, $attributes);
    }

    public function text(array $attributes)
    {
        $text = new TextHtml($this->context, $attributes);
        if (!$text->has('type')) {
            $text->set('type', 'text');
        }
        return $text;
    }

    public function textarea(array $attributes)
    {
        return new TextareaHtml($this->context, $attributes);
    }

    public function file(array $attributes)
    {
        $attributes['type'] = 'file';
        return new TextHtml($this->context, $attributes, 'file');
    }

    public function stylesheet(array $attributes)
    {
        $attributes['rel'] = 'stylesheet';
        return new Html($this->context, 'link', $attributes, null, true);
    }

    public function style(array $attributes)
    {
        $attributes['type'] = 'text/css';
        return new Html($this->context, 'style', $attributes);
    }

    public function script(array $attributes)
    {
        $attributes['type'] = 'text/javascript';
        return new Html($this->context, 'script', $attributes);
    }

    public function addIf2Html(Html $html, $if)
    {
        if ($if == '!IE') {
            $html->beforeWrap("<!--[if !IE]> -->", null, 1024);
            $html->afterWrap("<!-- <![endif]-->", null, 1024);
        } else {
            $html->beforeWrap("<!--[if {$if}]>", null, 1024);
            $html->afterWrap("<![endif]-->", null, 1024);
        }
    }

    public function progressBar(array $attributes)
    {
        $div = new Html($this->context, 'div', $attributes, 'progress-bar');
        $div->wrap($this->context->html('div', array(
                    'id' => $div->getId() . '-wrap'
                )), 'wrap');
        return $div;
    }

    public static function registerListeners()
    {
        return array(
            "kernel.html" => 'html',
            "kernel.html#checkbox" => 'checkbox',
            "kernel.html#message" => 'message',
            "kernel.html#radio" => 'radio',
            "kernel.html#select" => 'select',
            "kernel.html#form" => 'form',
            "kernel.html#text" => 'text',
            "kernel.html#textarea" => 'textarea',
            "kernel.html#file" => 'file',
            "kernel.html#stylesheet" => 'stylesheet',
            "kernel.html#style" => 'style',
            "kernel.html#script" => 'script',
            'kernel.html#button' => 'button',
            'kernel.html#cancel' => 'cancel',
            'kernel.html#submit' => 'submit',
            'kernel.html#hidden' => 'hidden',
            "kernel.html#progress-bar" => 'progressBar',
            "kernel.html@config:if" => 'addIf2Html'
        );
    }
}