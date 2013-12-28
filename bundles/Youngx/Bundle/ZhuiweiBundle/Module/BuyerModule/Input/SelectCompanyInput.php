<?php

namespace Youngx\Bundle\ZhuiweiBundle\Module\BuyerModule\Input;

use Youngx\MVC\Html\TextHtml;
use Youngx\MVC\Html;

class SelectCompanyInput extends TextHtml
{
    /**
     * @var Html
     */
    protected $hiddenHtml;

    protected function init()
    {
        parent::init();
        $this->set('autocomplete', 'off')
            ->set('placeholder', '请输入公司名称关键字');
        $this->hiddenHtml = $this->context->html('hidden', array(
                'name' => $this->get('name')
            ));
        $this->remove('name');
        $this->after($this->hiddenHtml, 'hidden');

        $this->context->assets()->registerPackage('bootstrap.autocomplete');
        $url = $this->context->generateUrl('company-ajax-autocomplete');

        $code = <<<code
$('#{$this->getId()}').autocomplete({
		source:function(query,process){
			var matchCount = this.options.items;
			$.getJSON("{$url}",{"keyword":query,"count":matchCount},function(respData){
				return process(respData);
			});
		},
		formatItem:function(item){
			return item["name"] + " (" + item["id"] + ")";
		},
		setValue:function(item, i){
		    if (i == 0) {
		        $('#{$this->hiddenHtml->getId()}').val(item["id"]);
		     }
			return {'data-value':item["name"] + " (" + item["id"] + ")",'real-value':item["id"]};
		},
		updater: function(item, realVal) {
		    $('#{$this->hiddenHtml->getId()}').val(realVal);
		    return item;
		}
	});
$('#{$this->getId()}').change(function() {
    if ($(this).val() == '') {
        $('#{$this->hiddenHtml->getId()}').val(0);
    }
});
code;
        $this->context->assets()->registerScriptCode($this->hiddenHtml->getId(), $code);

    }

    public function setValue($value)
    {
        $value = intval($value);
        $this->hiddenHtml->setValue($value);
        if ($value) {
            $company = $this->context->repository()->load('company', $value);
            if ($company) {
                parent::setValue($company->get('name') . ' ('.$company->identifier().')');
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