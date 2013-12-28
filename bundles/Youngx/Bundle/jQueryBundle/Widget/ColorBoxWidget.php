<?php

namespace Youngx\Bundle\jQueryBundle\Widget;

use Youngx\MVC\Html;
use Youngx\MVC\Widget;

class ColorBoxWidget extends Widget
{
    protected $wrapHtml;
    protected $pictures = array();

    protected function init()
    {
        $this->context->assets()->registerPackage('jquery.colorbox');
    }

    /**
     * @return Html
     */
    public function getWrapHtml()
    {
        if (null === $this->wrapHtml) {
            $this->wrapHtml = $this->context->html('ul');
        }
        return $this->wrapHtml;
    }

    protected function format($content)
    {
        $wrap = $this->getWrapHtml();

        $code = <<<code
var colorbox_params = {
reposition:true,
scalePhotos:true,
scrolling:false,
previous:'<i class="icon-arrow-left"></i>',
next:'<i class="icon-arrow-right"></i>',
close:'&times;',
current:'{current} of {total}',
maxWidth:'100%',
maxHeight:'100%',
onOpen:function(){
document.body.style.overflow = 'hidden';
},
onClosed:function(){
document.body.style.overflow = 'auto';
},
onComplete:function(){
$.colorbox.resize();
}
};
$('#{$wrap->getId()} [data-rel="colorbox"]').colorbox(colorbox_params);
code;

        $this->context->assets()->registerScriptCode($wrap->getId(), $code);
        return $wrap->setContent(implode("\n", $this->pictures) . $content);
    }

    /**
     * @param $thumbUrl
     * @param $url
     * @param array $actions
     * @return Html
     */
    public function addPicture($thumbUrl, $url, array $actions = array())
    {
        $this->pictures[] = $li = $this->context->html('li')->append(
            $this->context->html('a', array('href' => $url, 'data-rel' => 'colorbox'))
                ->append($this->context->html('img', array('src' => $thumbUrl), true), 'img'), 'link'
        );

        if ($actions) {
            $li->append($actionsHtml = $this->context->html('div'), 'actions');
            foreach ($actions as $name => $act) {
                $actionsHtml->append(
                    $this->context->html('a', is_array($act) ? $act : array('href' => $act)),
                    $name
                );
            }

        }

        return $li;
    }

    /**
     * @return Html[]
     */
    public function getPictureHtmls()
    {
        return $this->pictures;
    }

    public function name()
    {
        return 'jquery-colorbox';
    }
}