<?php

namespace Youngx\Bundle\AdminBundle\Module\AceModule\Listener;

use Youngx\Bundle\jQueryBundle\Input\ImageUploaderInput;
use Youngx\Bundle\jQueryBundle\Widget\ColorBoxWidget;
use Youngx\EventHandler\Event\GetSortableArrayEvent;
use Youngx\EventHandler\Registration;
use Youngx\MVC\Action\WizardAction;
use Youngx\MVC\Assets;
use Youngx\MVC\Context;
use Youngx\MVC\Html;
use Youngx\MVC\Html\CheckboxHtml;
use Youngx\MVC\Html\RadioHtml;
use Youngx\MVC\RenderableResponse;
use Youngx\MVC\Widget\BoxWidget;
use Youngx\MVC\Widget\BreadcrumbsWidget;
use Youngx\MVC\Widget\FormWidget;
use Youngx\MVC\Widget\NestedMenuWidget;
use Youngx\MVC\Widget\TableWidget;
use Youngx\MVC\Widget\WizardWidget;

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

    public function renderHeadBlock(GetSortableArrayEvent $event)
    {
        $event->set('viewport', '<meta name="viewport" content="width=device-width, initial-scale=1.0" />');
    }

    public function registerCoreAssets(Assets $assets)
    {
        $assets->registerPackage('jquery', array(
                '!IE' => '2.0.3',
                'IE' => '1.10.1'
            ));

        //$assets->registerPackage('jquery');
        $assets->registerPackage('bootstrap');

        $assets->registerStyleUrl('font-awesome', 'css/font-awesome.min.css');
        $assets->registerStyleUrl('font-awesome-ie7', array(
                'href' => 'Ace/css/font-awesome-ie7.min.css',
                '#if' => 'IE 7'
            ));
        $assets->registerStyleUrl('google-fonts', 'http://fonts.googleapis.com/css?family=Open+Sans:400,300');
        $assets->registerStyleUrl('ace', 'Ace/css/ace.min.css', 1024);
        $assets->registerStyleUrl('ace-rtl', 'Ace/css/ace-rtl.min.css', 1024);
        $assets->registerStyleUrl('ace-skins', 'Ace/css/ace-skins.min.css', 1024);
        $assets->registerStyleUrl('ace-ie', array(
                'href' => 'Ace/css/ace-ie.min.css',
                '#if' => 'lte IE 8'
            ), 1024);

        $assets->registerScriptUrl('ace-extra', 'Ace/js/ace-extra.min.js')->setHeadPosition();
        $assets->registerScriptUrl('html5shiv', array(
                'src' => 'js/html5shiv.js',
                '#if' => 'lt IE 9'
            ))->setHeadPosition();
        $assets->registerScriptUrl('respond', array(
                'src' => 'js/respond.min.js',
                '#if' => 'lt IE 9'
            ))->setHeadPosition();

        $assets->registerPackage('jquery.mobile');

        $assets->registerScriptUrl('ace-elements', 'Ace/js/ace-elements.min.js');
        $assets->registerScriptUrl('ace', 'Ace/js/ace.min.js');
    }

    public function layout(RenderableResponse $response)
    {
        $this->context->block('content', $response->getContent());
        $response->setContent($this->context->render('layouts/main.html.yui@Admin:Ace'));
    }

    public function loginLayout(RenderableResponse $response)
    {
        $this->context->block('body', $response->getContent());
        $response->setContent($this->context->render('layouts/html.yui@Kernel'));
    }

    public function formatBreadcrumbsWidget(BreadcrumbsWidget $widget)
    {
        $widget->getWrapHtml()->addClass('breadcrumb');
        $items = $widget->getItemHtmls();
        $home = reset($items);
        $home->prepend('<i class="icon-home home-icon"></i>');
        $last = end($items);
        $last->addClass('active');
    }

    public function formatBoxWidget(BoxWidget $widget)
    {
        $widget->getWrapHtml()->addClass('widget-box');
        $widget->getHeaderHtml()->addClass('widget-header');
        $widget->getBodyHtml()->addClass('widget-main')->wrap(
            $this->context->html('div', array('class' => 'widget-body'))
        );

        $widget->addToolbar('collapse', '<a data-action="collapse" href="#"><i class="icon-chevron-up"></i></a>');

        foreach ($widget->getToolbarHtmls() as $html) {
            $html->addClass('widget-toolbar');
        }
    }

    public function formatDatepickerInput(Html $html)
    {
        $html->set('#addon', array(
                'append' => '<i class="icon-calendar bigger-110"></i>'
            ));
    }

    public function formatSubtitleOption($subtitle)
    {
        if ($subtitle) {
            $content = '';
            if (is_string($subtitle)) {
                $content .= $subtitle;
            } else if (is_array($subtitle)) {
                $content .= array_shift($subtitle);
                foreach ($subtitle as $title) {
                    $content .= '<small><i class="icon-double-angle-right"></i> '.$title.'</small>';
                }
            }
            $this->context->block('subtitle', $content);
        }
    }


    public function formatSelectHtmlForChosen(Html $html)
    {
        $html->addClass('width-90 chosen-select');
    }

    public function formatTableWidget(TableWidget $table)
    {
        if ($table->getBatch()) {
            $table->getCheckboxThHtml()->addClass('center')->style('width', '5%');
            foreach ($table->getCheckboxTdHtmls() as $td) {
                $td->addClass('center');
            }
        }

        $buttonGroupWidget = $table->getButtonGroupWidget();
        if ($buttonGroupWidget) {
            //$buttonGroupWidget->getWrapHtml()->addClass('col-xs-6');
        }
        $pagingWidget = $table->getPagingWidget();
        if ($pagingWidget) {
            $pagingWidget->getWrapHtml()->addClass('center');
        }

//        $table->getActionsWrapHtml()
//            ->sortContent('paging', 1)
//            ->sortContent('button-group', 2);
    }

    public function formatCheckboxHtml(CheckboxHtml $checkbox)
    {
        if ($checkbox->getLabel()) {
            $checkbox->addClass('ace');
            $checkbox->find('wrap')->addClass('inline');
            $checkbox->find('label')->addClass('lbl');
        }
    }

    public function formatRadioHtml(RadioHtml $checkbox)
    {
        if ($checkbox->getLabel()) {
            $checkbox->addClass('ace');
            $checkbox->find('wrap')->addClass('inline');
            $checkbox->find('label')->addClass('lbl');
        }
    }

    public function formatWizardWidget(WizardWidget $widget)
    {
        $widget->getHeaderHtml()->addClass('wizard-steps')
            ->wrap($this->context->html('div', array('class' => 'row-fluid')))
            ->afterWrap('<hr />');

        if ($widget->getStep() > 0) {
            foreach ($widget->getStepHtmls() as $i => $li) {
                if ($i < $widget->getStep()) {
                    $li->addClass('complete');
                } else {
                    break;
                }
            }
        }

        $widget->getActiveStepHtml()->addClass('active');
        $widget->getContentWrapHtml()->addClass('step-content row-fluid');

        $widget->getActionsWrapHtml()->addClass('row-fluid wizard-actions')->beforeWrap('<hr />');

        $widget->getPrevHtml()->addClass('btn btn-prev')->prepend('<i class="icon-arrow-left"></i>');
        $widget->getNextHtml()->addClass('btn btn-success btn-next')->append('<i class="icon-arrow-right icon-on-right"></i>');

        foreach ($widget->getStepHtmls() as $html) {
            $html->find('step')->addClass('step');
            $html->find('title')->addClass('title');
        }
    }

    public function renderWizardAction(RenderableResponse $response, WizardAction $action)
    {
        $response->setContent($this->context->widget('Wizard', array(
                    '#content' => $response->getContent(),
                    '#next' => array(),
                    '#step' => $action->getStep(),
                    '#steps' => $action->getStepTitles()
                ))) ;
    }

    public function formatFileInput(Html $file)
    {
        $multiple = (Boolean) $file->get('multiple', false);

        if ($multiple) {
            $file->setName($file->get('name') . '[]');
        }

        $options = array(
            'no_file' => '请选择文件',
            'btn_choose' => '选择',
            'btn_change' => '更改',
        );

        if ($multiple) {
            $options['thumbnail'] = 'small';
            $options = array_merge($options, array(
                    'thumbnail' => 'small',
                    'style' => 'well',
                    'no_icon' => 'icon-cloud-upload',
                    'droppable' => true
                ));
        }

        $options = json_encode($options);

        $code = <<<code
$('#{$file->getId()}').ace_file_input({$options});
code;
        $this->context->assets()->registerScriptCode($file->getId(), $code);

    }

    public function renderSidebarMenuBlock(GetSortableArrayEvent $event)
    {
        $current = $this->context->request()->getRouteName();
        if ($current) {
            $event->set('nestedMenu', $widget = $this->context->widget('NestedMenu', array(
                        '#items' => $this->context->get('web')->getNestedMenus($this->context->router()->getRootWithinGroup($current))
                    )));
            $this->formatNestedMenuWidget($widget);
        }
    }

    public function formatNestedMenuWidget(NestedMenuWidget $widget)
    {
        $widget->getWrapHtml()->addClass('nav nav-list');

        $router = $this->context->router();
        $name = $this->context->request()->getRouteName();
        $current = $name;
        while ($current && null === ($html = $widget->getItemHtml($current))) {
            $current = $router->getMenu($current)->getParent();
        }
        if (isset($html)) {
            $html->addClass('active');
        }

        foreach ($widget->getSubWrapHtmls() as $html) {
            $html->addClass('submenu');
        }

        while (null !== ($parent = $widget->getItemParentName($current))) {
            $widget->getItemHtml($parent)->addClass('active open');
            $current = $parent;
        }

        foreach ($widget->getParentItemHtmls() as $name => $html) {
            if ($widget->getItemParentName($name)) {
                $widget->getItemLinkHtml($name)
                    ->addClass('dropdown-toggle')
                    ->append('<b class="arrow icon-angle-down"></b>');
            }
        }

        foreach ($widget->getItemParentNames() as $name => $parent) {
            if ($parent === null) {
                $link = $widget->getItemLinkHtml($name);
                $link->setContent('<span class="menu-text"> '.$link->getContent().' </span>');
            }
        }

        foreach ($widget->getSubItemNames() as $name) {
            $link = $widget->getItemLinkHtml($name);
            if (!$link->hasConfig('icon')) {
                $link->set('#icon', 'double-angle-right');
            }
        }
    }

    public function renderSubmenuBlock(GetSortableArrayEvent $event)
    {
        $name = $this->context->request()->getRouteName();
        //$menuRoot = $this->context->request()->getRouteName();
        if ($name && null !== ($menuRoot = $this->context->router()->getMenuRoot($name))) {
        //if ($menuRoot) {
            $nestedMenus = $this->context->get('web')->getNestedMenus($menuRoot);
            if ($nestedMenus) {
                $event->set('nestedMenu', $widget = $this->context->widget('NestedMenu', array(
                            '#items' => $nestedMenus
                        )));
                $this->formatNestedMenuForSubMenu($widget);
            }
        }
    }

    protected function formatNestedMenuForSubMenu(NestedMenuWidget $widget)
    {
        $widget->getWrapHtml()->addClass('nav nav-pills');
        foreach ($widget->getParentItemHtmls() as $name => $li) {
            $li->addClass('dropdown');
            $widget->getItemLinkHtml($name)
                ->append('<span class="caret"></span>')
                ->set('data-toggle', 'dropdown');
        }

        foreach ($widget->getSubWrapHtmls() as $html) {
            $html->addClass('dropdown-menu');
        }
    }

    public function formatColorBoxWidget(ColorBoxWidget $widget)
    {
        $widget->getWrapHtml()->addClass('ace-thumbnails');
        foreach ($widget->getPictureHtmls() as $li) {
            $li->find('link')->addClass('cboxElement');
            $actionsWrap = $li->find('actions');
            if ($actionsWrap) {
                $actionsWrap->addClass('tools tools-bottom');
            }
        }
    }

    public function formatSearchFormWidget(FormWidget $form)
    {
        $form->setSkin('inline');

        $form->getFormHtml()->set('method', 'get')->addClass('form-inline');

        $form->setSubmit(array(
                '#content' => '搜索',
                'class' => 'btn btn-primary'
            ));

        $form->getActionsWrapHtml()->addClass('form-group');

        $form->wrap('box', $this->context->widget('Box', array(
                    '#title' => '过滤条件'
                )));
    }

    public function formatViewForTabMenu(RenderableResponse $response)
    {
        $current = $this->context->request()->getMenu();
        if ($current && $current->isTab()) {
            $router = $this->context->router();
            $tabs = $menus = array();

            if ($current->isTabDefault()) {
                $parent = $this->context->request()->getRouteName();
                $menus = $router->getSubMenus($parent);
                if ($current->isTabDefaultSelf()) {
                    $menus = array($parent => $current) + $menus;
                }
            } else if ($current->getParent()) {
                $parent = $router->getMenu($current->getParent());
                $menus = ($parent->isTabDefaultSelf() ? array($current->getParent() => $parent) : array()) + $router->getSubMenus($current->getParent());
            }

            if ($menus) {
                foreach ($menus as $name => $menu) {
                    if ($menu->isTab()) {
                        $tabs[$name] = array_merge(array(
                                '#content' => $menu->getLabel(),
                                'href' => $this->context->generateUrlWithCurrent($name, true)
                            ), $menu->getAttributes());
                    }
                }
            }

            if ($tabs) {
                $active = $this->context->request()->getRouteName();
                $tab = $this->context->widget('Tab', array('#tabs' => $tabs, '#active' => $active));
                $response->setContent($tab->content($active, $response->getContent()));
            }
        }
    }

    public function formatImageUploaderHtml(ImageUploaderInput $input)
    {
        $input->find('file')->find('button')->addClass('btn btn-primary');
    }

    public function renderMessages(GetSortableArrayEvent $event)
    {
        foreach ($this->context->flash()->all() as $type => $messages) {
            foreach ($messages as $i => $message) {
                $event->set("{$type}.{$i}", $this->context->html('message', array(
                            '#type' => $type,
                            '#content' => $message,
                            '#skin' => 'ace'
                        )), -100);
            }
        }
    }

    public function formatMessageHtml(Html\MessageHtml $html)
    {
        $type = $html->getType();

        if ($type == 'error') {
            $type = 'danger';
            $icon = 'remove';
        } else {
            if ($type == 'success') {
                $icon = 'ok';
            }
        }

        $html->addClass('alert-'.$type);

        if (isset($icon)) {
            $html->set('#icon', $icon);
        }

        $html->addClass('alert')
            ->prepend($this->context->html('button', array(
                        '#icon' => 'remove',
                        'data-dismiss' => 'alert',
                        'class' => 'close'
                    )));
    }

    public static function registerListeners()
    {
        return array(
            'kernel.assets@menu-group:admin' => 'registerCoreAssets',
            'kernel.block#head@menu-group:admin' => 'renderHeadBlock',
            'kernel.block#sidebar@menu-group:admin' => 'renderSidebarMenuBlock',
            'kernel.block#submenu@menu-group:admin' => 'renderSubmenuBlock',
            'kernel.block#content@menu-group:admin' => 'renderMessages',
            'kernel.renderable.config#subtitle@menu-group:admin' => 'formatSubtitleOption',
            'kernel.renderable.config#submenu@menu-group:admin' => 'formatSubmenuConfig',
            'kernel.renderable.layout@menu-group:admin' => 'layout',
            'kernel.renderable.layout@menu:admin-login' => 'loginLayout',
            'kernel.renderable.format@menu-group:admin' => 'formatViewForTabMenu',
            'kernel.widget.format#breadcrumbs@menu-group:admin' => 'formatBreadcrumbsWidget',
            'kernel.widget.format#jquery-colorbox@menu-group:admin' => 'formatColorBoxWidget',
            'kernel.widget.format#table' => 'formatTableWidget',
            'kernel.widget.format#box' => 'formatBoxWidget',
            'kernel.widget.format#form@skin:search' => 'formatSearchFormWidget',
            "kernel.html.format#checkbox@menu-group:admin" => 'formatCheckboxHtml',
            "kernel.html.format#radio@menu-group:admin" => 'formatRadioHtml',
            "kernel.html.format#image-uploader@menu-group:admin" => 'formatImageUploaderHtml',
            'kernel.html#select@config:chosen' => 'formatSelectHtmlForChosen',
            'kernel.widget.format#wizard' => 'formatWizardWidget',
            'kernel.action.render#wizard' => 'renderWizardAction',
            'kernel.input.format#file' => 'formatFileInput',
            'kernel.input.format#datepicker' => 'formatDatepickerInput',
            'kernel.html#message@skin:ace' => 'formatMessageHtml',
        );
    }
}