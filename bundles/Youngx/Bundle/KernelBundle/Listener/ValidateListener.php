<?php

namespace Youngx\Bundle\KernelBundle\Listener;

use Youngx\MVC\Application;
use Youngx\MVC\Form;
use Youngx\MVC\ListenerRegistry;

class ValidateListener implements ListenerRegistry
{
    protected $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    public function validate(Form $form, $name, array $arguments, $validator)
    {
        return $this->app->validate($validator, $form->get($name), $arguments);
    }

    public function required($value)
    {
        return !empty($value);
    }

    public function rangelength($value, $min, $max)
    {
        return !(($n = strlen($value) < $min) || $n > $max);
    }

    public function range($value, $min, $max)
    {
        $value = floatval($value);
        return $min <= $value && $value <= $max;
    }

    public function email($value)
    {
        return (Boolean) strpos($value, '@');
    }

    public function equalTo(Form $form, $name, array $arguments)
    {
        return $form->get($name) == $form->get($arguments[0]);
    }

    public function name($value)
    {
        return preg_match('/^[a-z][a-z0-9_]{2,32}$/', $value);
    }

    public static function registerListeners()
    {
        return array(
            'kernel.validate#required' => 'required',
            'kernel.validate#rangelength' => 'rangelength',
            'kernel.validate#range' => 'range',
            'kernel.validate#email' => 'email',
            'kernel.validate#name' => 'name',
            'kernel.validate.form' => 'validate',
            'kernel.validate.form#equalTo' => 'equalTo',
        );
    }
}