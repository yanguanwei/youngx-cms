<?php

namespace Youngx\Bundle\jQueryBundle\Listener;

use Youngx\EventHandler\Registration;
use Youngx\MVC\Context;
use Youngx\MVC\Html;
use Youngx\MVC\Assets;
use Youngx\MVC\Widget\TreeTableWidget;

class FormatListener implements Registration
{
    /**
     * @var Context $context
     */
    protected $context;

    public function setContext(Context $context)
    {
        $this->context = $context;
    }

    public function formatSelectForChosen(Html $html, $chosen)
    {
        $id = $html->getId();
        $config = is_array($chosen) ? json_encode($chosen) : '';
        $this->context->assets()->registerPackage('jquery.chosen');
        $this->context->assets()->registerScriptCode($id, sprintf('$("#%s").chosen(%s);', $id, $config));
    }

    public function formatTreeTableWidget(TreeTableWidget $widget)
    {
        if ($this->context->request()->isMethod('GET')) {
            $json = array();
            $entities = $widget->getTable()->getEntities();
            foreach ($widget->getTdHtmlRows() as $i => $row) {
                $json[] = array(
                    'data' => array(
                        'rows' => $row,
                        'entity' => $entities[$i]->toArray(),
                    ),
                    'child' => $widget->getTable()->hasChildForEntity($entities[$i])
                );
            }

            if ($json) {
                $json = json_encode(array(
                        'items' => $json
                    ));
            } else {
                $json = 'null';
            }

            $this->context->assets()->registerPackage('jquery.tabletree');

            $code = <<<code
$('#{$widget->getTableHtml()->getId()}').tabletree({
    url: function() {
        return '{$this->context->request()->getUri()}';
    },
    data: function(data) {
        var entity = data.entity;
        {$widget->getTable()->getPostDataForScriptCodes()}
    },
    format: function( data, child, next ) {
        for (var name in data.rows) {
            this.tr.append(data.rows[name]);
        }
    }
}, {$json});
code;
            $this->context->assets()->registerScriptCode($widget->getTableHtml()->getId(), $code);
        }
    }

    public static function registerListeners()
    {
        return array(
            'kernel.html#select@config:chosen' => 'formatSelectForChosen',
            'kernel.widget.format#tree-table' => 'formatTreeTableWidget'
        );
    }
}