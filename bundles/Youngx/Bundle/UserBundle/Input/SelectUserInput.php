<?php

namespace Youngx\Bundle\UserBundle\Input;

use Youngx\MVC\Html\TextHtml;
use Youngx\MVC\Html;

class SelectUserInput extends TextHtml
{
    /**
     * @var Html
     */
    protected $hiddenHtml;

    protected function init()
    {
        parent::init();
        $this->set('autocomplete', 'off');
        $this->hiddenHtml = $this->context->html('hidden', array(
                'name' => $this->get('name')
            ));
        $this->remove('name');
        $this->after($this->hiddenHtml, 'hidden');
    }

    public function setValue($value)
    {
        $value = intval($value);
        $this->hiddenHtml->setValue($value);
        if ($value) {
            $user = $this->context->repository()->load('user', $value);
            if ($user) {
                parent::setValue($user->get('name') . ' ('.$user->identifier().')');
            }
        }
    }

    /**
     * @return Html
     */
    public function getHiddenHtml()
    {
        return $this->hiddenHtml;
    }
}