<?php

namespace Youngx\Bundle\jQueryBundle\Input;

use Youngx\Bundle\AdminBundle\Module\FileModule\Entity\FileEntity;
use Youngx\MVC\Context;
use Youngx\MVC\Html;

class ImageUploaderInput extends Html
{
    protected $url;
    protected $imagePlaceholder;
    protected $button = '请选择图片';
    protected $previewWidth = 0;
    protected $previewHeight = 0;

    public function __construct(Context $context, array $attributes)
    {
        parent::__construct($context, 'input', $attributes, 'image-uploader', true);
        $this->set('type', 'hidden');
    }

    protected function init()
    {
        $this->after($this->context->html('img', array(
                    'src' => $this->imagePlaceholder
                ), true), 'preview');
        $this->after($file = $this->context->html('file', array(
                    'style' => 'position:absolute; right:0; top:0; font-size:100px; opacity:0; filter:alpha(opacity=0)'
                )), 'file');

        $file->wrap($a = $this->context->html('a', array(
                    'style' => 'display:inline-block; position:relative; overflow:hidden'
                )), 'button');

        if (is_array($this->button)) {
            $a->set($this->button);
        } else {
            $a->append($this->button);
        }

    }

    public function setValue($value)
    {
        $this->set('value', $value);

        parent::setValue($value);
    }

    protected function format()
    {
        $fileHtml = $this->find('file');
        $this->context->assets()->registerScriptUrl('ajaxupload', 'jQuery/ajaxupload/ajaxupload.js');

        $preview = $this->find('preview');

        $value = $this->getValue();

        if ($value) {
            $file = $this->context->repository()->load('file', $value);
            if ($file && $file instanceof FileEntity) {
                $preview->set('src', $this->context->locateImageUrl($file->getUri(), $this->previewWidth, $this->previewHeight));
            }
        }

        $code = <<<code
var preview = $('#{$preview->getId()}');
var hiddenInput = $('#{$this->getId()}');
new AjaxUpload('{$fileHtml->getId()}', {
    action: '{$this->url}',
    name: 'file',
    data: {previewWidth: {$this->previewWidth}, previewHeight: {$this->previewHeight}},
    onSubmit: function(file, extension) {
        var allows = ['jpg', 'jpeg', 'gif', 'png', 'bmp'];

        if (-1 == $.inArray(extension.toLowerCase(), allows)) {
            alert("请选择合法的图片文件！");
            return false;
        }

        this._settings.data.old_file = hiddenInput.val() || 0;
    },
    onComplete: function(file, response) {
        try {
            var json = $.parseJSON(response);
            if (json) {
                preview.attr('src', json.url);
                hiddenInput.val(json.id);
            }
        } catch (e) {
            alert(response);
        }
    }
});
code;
        $this->context->assets()->registerScriptCode($fileHtml->getId(), $code);
    }

    /**
     * @param mixed $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }

    /**
     * @return mixed
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param mixed $imagePlaceholder
     */
    public function setImagePlaceholder($imagePlaceholder)
    {
        $this->imagePlaceholder = $imagePlaceholder;
    }

    /**
     * @return mixed
     */
    public function getImagePlaceholder()
    {
        return $this->imagePlaceholder;
    }

    /**
     * @param mixed $button
     */
    public function setButton($button)
    {
        $this->button = $button;
    }

    /**
     * @return mixed
     */
    public function getButton()
    {
        return $this->button;
    }

    /**
     * @param int $previewHeight
     */
    public function setPreviewHeight($previewHeight)
    {
        $this->previewHeight = intval($previewHeight);
    }

    /**
     * @return int
     */
    public function getPreviewHeight()
    {
        return $this->previewHeight;
    }

    /**
     * @param int $previewWidth
     */
    public function setPreviewWidth($previewWidth)
    {
        $this->previewWidth = intval($previewWidth);
    }

    /**
     * @return int
     */
    public function getPreviewWidth()
    {
        return $this->previewWidth;
    }
}