<?php

namespace Youngx\Bundle\KernelBundle\Module\CKFinderModule\Listener;

use Youngx\EventHandler\Registration;
use Youngx\MVC\Assets;
use Youngx\MVC\Context;
use Youngx\MVC\Html;
use Youngx\MVC\User\Identity;

class ViewListener implements Registration
{
    /**
     * @var Context
     */
    private $context;

    public function setContext(Context $context)
    {
        $this->context = $context;
    }

    public function formatCKEditorInput(Html $html)
    {
        $assets = $this->context->assets();
        if (null !== ($asset = $assets->getScriptCode("{$html->getId()}.ckeditor"))) {
            $assets->registerPackage('ckfinder');
            $asset->set('ckfinder', "CKFinder.setupCKEditor(ckeditor, '{$this->context->assets()->url('CKFinder/')}' )", 10);
        }
    }

    public function ckfinderPackage(Assets $assets)
    {
        $assets->registerScriptUrl('ckfinder', 'CKFinder/ckfinder.js');
    }

    public function ckfinderInput(array $attributes)
    {
        $text = $this->context->input('text', $attributes);

        $this->context->assets()->registerPackage('ckfinder');
        $text->set('#addon', array(
                'append' => $button = $this->context->html('button', array(
                        'class' => 'btn btn-sm btn-default',
                        '#content' => '<i class="icon-picture bigger-110"></i>'
                    )),
                'append-type' => 'btn'
            ));

        $basePath = $this->context->assets()->url('CKFinder/');
        $code = <<<code
$('#{$button->getId()}').click(function() {
    var finder = new CKFinder();
	finder.basePath = '{$basePath}';
    finder.startupPath = 'Images:/';
	finder.selectActionFunction = function(fileUrl) {
        $('#{$text->getId()}').val(fileUrl);
	};
	finder.popup();

});
code;
        $this->context->assets()->registerScriptCode("{$text->getId()}.ckfinder", $code);

        return $text;
    }

    public function onUserLogin(Identity $identity)
    {
        if ($identity->getId() == 1 || $identity->hasRole(Identity::ROLE_ADMINISTRATOR)) {
            $this->context->session()->set('CKFinder_identity', 1);
        }
    }

    public function onUserLogout(Identity $identity)
    {
        if ($identity->getId() == 1 || $identity->hasRole(Identity::ROLE_ADMINISTRATOR)) {
            $this->context->session()->remove('CKFinder_identity');
        }
    }

    public static function registerListeners()
    {
        return array(
            'kernel.assets.package#ckfinder' => 'ckfinderPackage',
            'kernel.input.format#ckeditor' => 'formatCKEditorInput',
            'kernel.input#ckfinder' => 'ckfinderInput',
            'user.login' => 'onUserLogin',
            'user.logout' => 'onUserLogout',
        );
    }
}