<?php

namespace Youngx\Bundle\jQueryBundle\Input;

use Youngx\MVC\Context;
use Youngx\MVC\Html;

class CXSelectInput extends Html
{
    protected $url;
    protected $nodata = 'none';
    protected $selects;
    protected $required = false;
    protected $selectHtmls = array();
    protected $titles = array();
    protected $values = array();
    protected $name;

    public function __construct(Context $context, array $attributes = array())
    {
        parent::__construct($context, 'div', $attributes, 'cxselect');
    }

    protected function init()
    {
        $this->context->assets()->registerPackage('jquery.cxselect');
    }

    protected function format()
    {
        $options = array(
            'url' => $this->url,
            'required' => $this->required,
            'nodata' => $this->nodata
        );

        $hidden = $this->context->html('hidden', array(
                'name' => $this->name,
                'value' => $this->getValue()
            ));

        $this->append($hidden);

        $selects = array();

        foreach ($this->selects as $i => $name) {
            $selects[] = $class = 'cxselect-' . $name;

            $attributes = array(
                'class' => $class
            );
            if (isset($this->titles[$name])) {
                $attributes['data-title'] = $this->titles[$name];
            }
            if (isset($this->values[$name])) {
                $attributes['data-val'] = $this->values[$name];
            } else if ($i>0) {
                $attributes['style'] = array(
                    'display' => 'none'
                );
            }

            $this->append(
                $this->selectHtmls[$name] = $this->context->html('select', $attributes)
            );
        }

        $options['selects'] = $selects;

        $options = json_encode($options);

        $code = <<<code
$("#{$this->getId()}").cxSelect({$options});
$("#{$this->getId()}").find('select').change(function() {
    $('#{$hidden->getId()}').val($(this).val());
});
code;
        $this->context->assets()->registerScriptCode($this->getId(), $code);


    }

    public function setSelects(array $selects)
    {
        foreach ($selects as $name) {
            $this->selects[] = $name;
        }
    }

    public function setSelectsTotal($total)
    {
        $total = intval($total);
        $selects = array();
        for($i = 0; $i<$total; $i++) {
            $selects[] = $i;
        }
        $this->setSelects($selects);
    }

    public function setTitles(array $titles)
    {
        foreach ($titles as $name => $title) {
            $this->titles[$name] = $title;
        }
    }

    public function setValues(array $values)
    {
        foreach ($values as $name => $value) {
            $this->values[$name] = $value;
        }
    }

    public function setRequired($required)
    {
        $this->required = $required;
    }

    /**
     * @return Html[]
     */
    public function getSelectHtmls()
    {
        return $this->selectHtmls;
    }

    public function setUrl($url)
    {
        $this->url = $url;
    }

    public function setNodata($nodata)
    {
        $this->nodata = $nodata;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }
}