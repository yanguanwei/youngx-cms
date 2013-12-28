<?php

namespace Youngx\Bundle\KernelBundle\Module\BootstrapModule\Listener;

use Youngx\EventHandler\Registration;
use Youngx\MVC\Assets;
use Youngx\MVC\Html;
use Youngx\MVC\Context;
use Youngx\MVC\Widget\ButtonGroupWidget;
use Youngx\MVC\Widget\PagingWidget;
use Youngx\MVC\Widget\TableWidget;
use Youngx\MVC\Widget\FieldWidget;
use Youngx\MVC\Widget\FormWidget;
use Youngx\MVC\Widget\TabWidget;

class MainListener implements Registration
{
    /**
     * @var Context
     */
    protected $context;

    public function setContext(Context $context)
    {
        $this->context = $context;
    }

    public function bootstrapPackage(Assets $assets)
    {
        $assets->registerScriptUrl('bootstrap', 'Bootstrap/js/bootstrap.js');
        $assets->registerStyleUrl('bootstrap', 'Bootstrap/css/bootstrap.css');
    }

    public function autocompletePackage(Assets $assets)
    {
        $assets->registerScriptUrl('bootstrap-typeahead', 'Bootstrap/js/autocomplete.js');
    }

    public function datepickerPackage(Assets $assets)
    {
        $assets->registerScriptUrl('bootstrap-datepicker', 'Bootstrap/js/date-time/bootstrap-datepicker.min.js');
        $assets->registerStyleUrl('bootstrap-datepicker', 'Bootstrap/css/date-time/datepicker.css');
    }

    public function datepickerInput(array $attributes = array())
    {
        $text = $this->context->input('text', $attributes);
        $this->context->assets()->registerPackage('bootstrap.datepicker');
        $options = array(
            'autoclose' => true,
            'format' => 'yyyy-mm-dd'
        );

        if ($text instanceof Html) {
            $options = array_merge($options, $text->getConfig('datepicker', array()));
        }

        $options = json_encode($options);
        $code = <<<code
$('#{$text->get('id')}').datepicker({$options}).next().on('click', function(){
					$(this).prev().focus();
				});
code;
        $this->context->assets()->registerScriptCode("datepicker#{$text->get('id')}", $code);

        return $text;
    }

    public function formatSizeConfig(Html $html, $size)
    {
        $html->addClass($size);
    }

    public function formatFieldWidget(FieldWidget $field)
    {
        $inputs = $field->getInputs();
        if (count($inputs) == 1) {
            $input = reset($inputs);
            if ($input instanceof Html) {
                if ( ($input->getTag() == 'input' && in_array($input->get('type'), array('text', 'password')))
                    || in_array($input->getTag(), array('select', 'textarea')) )
                {
                    $input->addClass('form-control');
                }
            }
        }

        if (null !== ($help = $field->getHelpHtml())) {
            $help->addClass('help-block');
        }

        if ($field->getLabelHtml()) {
            $field->getWrapHtml()->addClass('form-group');
        }

        if (null !== ($error = $field->getErrorHtml())) {
            $field->getWrapHtml()->addClass('has-error');
            $error->addClass('help-block');
        }

        if ($field->getFormWidget()) {
            $skin = $field->getFormWidget()->getSkin();
            switch ($skin) {
                case 'inline':
                    $this->formatInlineFormFieldWidget($field);
                    break;
                case 'horizontal':
                    $this->formatHorizontalFieldWidget($field);
                    break;

            }
        }
    }

    protected function formatInlineFormFieldWidget(FieldWidget $field)
    {
        if (null !== ($label = $field->getLabelHtml())) {
            $label->addClass('sr-only');
            $input = $field->getInput();
            if ($input && $input instanceof Html) {
                if (!$input->has('placeholder')) {
                    $input->set('placeholder', $label->getContent());
                }
            }
        }
    }

    public function formatHorizontalFieldWidget(FieldWidget $field)
    {
        if (null !== ($label = $field->getLabelHtml())) {
            $label->addClass('col-sm-3 control-label no-padding-right');
        }
        if (null !== ($inputWrap = $field->getInputWrapHtml())) {
            $inputWrap->addClass('col-xs-12 col-sm-5');
            if (!isset($label)) {
                $inputWrap->addClass('col-sm-offset-3');
            }
        }

        if (null !== ($error = $field->getErrorHtml())) {
            $error->addClass('col-xs-12 col-sm-reset inline');
        }
    }

    public function formatFormWidget(FormWidget $form)
    {
        if ($form->get('cancel',  false)) {
            $cancel = $form->get('cancel');
            if (!is_array($cancel)) {
                $cancel = array(
                    'href' => $cancel
                );
            }
            $form->getButtonGroupWidget()->add('cancel', $this->context->html('cancel', $cancel)->addClass('btn'));
        }
    }

    public function formatInlineFormWidget(FormWidget $form)
    {
        $form->getFormHtml()->addClass('form-inline');
    }

    public function formatVerticalFormWidget(FormWidget $form)
    {
        $form->getActionsWrapHtml()->addClass('form-actions center');
        $form->getSubmitHtml()->addClass('btn btn-primary');
    }

    public function formatHorizontalFormWidget(FormWidget $form)
    {
        $form->getFormHtml()->addClass('form-horizontal');

        $form->getActionsWrapHtml()->addClass('col-md-offset-3 col-md-9')
            ->wrap($this->context->html('div', array('class' => 'clearfix form-actions')));

        $form->getSubmitHtml()->addClass('btn btn-primary');
    }

    public function formatAddonOption(Html $html, $addon)
    {
        $html->wrap($this->context->html('div', array('class' => 'input-group')));
        if (!is_array($addon)) {
            $addon = array(
                'prepend' => $addon
            );
        }

        if (isset($addon['prepend'])) {
            $html->before($this->context->html('span', array(
                        'class' => 'input-group-' . (isset($addon['prepend-type']) ? $addon['prepend-type'] : 'addon'),
                        '#content' => $addon['prepend']
                    )));
        }

        if (isset($addon['append'])) {
            $html->after($this->context->html('span', array(
                        'class' => 'input-group-' . (isset($addon['append-type']) ? $addon['append-type'] : 'addon'),
                        '#content' => $addon['append']
                    )));
        }
    }

    public function formatTableWidget(TableWidget $table)
    {
        $table->getTableHtml()->addClass('table table-striped table-bordered table-hover');
    }

    public function formatTabWidget(TabWidget $tab)
    {
        $tab->getWrapHtml()->addClass('tabbable');
        if ($tab->getPosition()) {
            $tab->getWrapHtml()->addClass('tabs-' . $tab->getPosition());
        }

        $tab->getTabHtml()->addClass('nav nav-tabs');
        foreach ($tab->getTabListHtmls() as $id => $list) {
            $link = $list->find('link');
            if ($link->get('href') && substr($link->get('href'), 0, 1) == '#') {
                $link->set('data-toggle', 'tab');
            }

            if ($link->hasConfig('dropdown')) {
                $link
                    ->set('data-toggle', 'dropdown')
                    ->addClass('dropdown-toggle')
                    ->append('<b class="caret"></b>')
                    ->after($ul = $this->context->html('ul', array('class' => 'dropdown-menu')));

                $list->addClass('dropdown');

                foreach ($link->getConfig('dropdown') as $key => $config) {
                    $ul->append($li = $this->context->html('li', array(
                                '#content' => $a = $this->context->html('a', $config)
                            )));
                    if ($key == $tab->getActive()) {
                        $li->addClass($tab->getActiveClass());
                        $list->addClass($tab->getActiveClass());
                    }

                    $a->set('data-toggle', 'tab');

                    if (is_string($config)) {
                        $a->set('href', "#{$key}");
                    }
                }
            }
        }
        $tab->getContentWrapHtml()->addClass('tab-content');
        foreach ($tab->getContentHtmls() as $html) {
            $html->addClass('tab-pane');
        }
    }

    public function formatPagingWidget(PagingWidget $paging)
    {
        $paging->getUlHtml()->addClass('pagination');
    }

    public function addIcon2Html(Html $html, $icon)
    {
        $html->prepend('<i class="icon-'.$icon.'"></i>');
    }

    public function formatProgressBarHtml(Html $html)
    {
        $html->addClass('progress-bar')
            ->find('wrap')->addClass('progress');
    }

    public function formatFormHtmlDirectionOption(Html $html, $value)
    {
        $html->addClass("form-{$value}");
    }

    public function formatButtonGroupWidget(ButtonGroupWidget $widget)
    {
        $widget->getWrapHtml()->addClass('btn-group');
        foreach ($widget->getButtonGroupWrapHtmls() as $wrap) {
            $wrap->addClass('btn-group');
            $wrap->find('ul')->addClass('dropdown-menu');
            $wrap->find('button')
                ->addClass('dropdown-toggle')
                ->set('data-toggle', 'dropdown')
                ->append('<span class="icon-angle-down icon-on-right"></span>');
        }

        foreach ($widget->getButtonHtmls() as $btn) {
            $btn->addClass('btn btn-primary');
        }
    }

    public static function registerListeners()
    {
        return array(
            'kernel.assets.package#bootstrap' => 'bootstrapPackage',
            'kernel.assets.package#bootstrap.autocomplete' => 'autocompletePackage',
            'kernel.assets.package#bootstrap.datepicker' => 'datepickerPackage',
            'kernel.input#datepicker' => 'datepickerInput',
            'kernel.widget.format#field' => 'formatFieldWidget',
            'kernel.widget.format#form' => 'formatFormWidget',
            'kernel.widget.format#form@skin:inline' => 'formatInlineFormWidget',
            'kernel.widget.format#form@skin:vertical' => 'formatVerticalFormWidget',
            'kernel.widget.format#form@skin:horizontal' => 'formatHorizontalFormWidget',
            'kernel.widget.format#table' => 'formatTableWidget',
            'kernel.widget.format#button-group' => 'formatButtonGroupWidget',
            'kernel.widget.format#tree-table' => 'formatTableWidget',
            'kernel.widget.format#tab' => 'formatTabWidget',
            'kernel.widget.format#paging' => 'formatPagingWidget',
            'kernel.html#text@config:addon' => 'formatAddonOption',
            "kernel.html@config:icon" => 'addIcon2Html',
            "kernel.html@config:size" => 'formatSizeConfig',
            'kernel.html.format#progress-bar' => 'formatProgressBarHtml',
        );
    }
}