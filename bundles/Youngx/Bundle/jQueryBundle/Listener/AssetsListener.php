<?php

namespace Youngx\Bundle\jQueryBundle\Listener;

use Youngx\EventHandler\Registration;
use Youngx\MVC\Assets;

class AssetsListener implements Registration
{
    public function jqueryPackage(Assets $assets, $versions = null)
    {
        if ($versions) {
            if (is_array($versions)) {
                foreach ($versions as $if => $version) {
                    $assets->registerScriptUrl("jquery-{$if}", array(
                            'src' => "jQuery/jquery-{$version}.min.js",
                            '#if' => $if
                        ));
                }
            } else {
                $assets->registerScriptUrl('jquery', "jQuery/jquery-{$versions}.min.js");
            }
        } else {
            $assets->registerScriptUrl('jquery', "jQuery/jquery-1.7.2.js");
        }
    }

    public function datepickerPackage(Assets $assets)
    {
        $assets->registerScriptUrl('jquery-ui', 'jQuery/ui/jquery-ui-1.8.21.custom.min.js');
        $assets->registerStyleUrl('jquery-ui', 'jQuery/ui/jquery-ui-1.8.21.custom.css');
    }

    public function uniformPackage(Assets $assets)
    {
        $assets->registerScriptUrl('jquery-uniform', 'jQuery/uniform/jquery.uniform.min.js');
        $assets->registerStyleUrl('jquery-uniform', 'jQuery/uniform/uniform.default.css');
    }

    public function chosenPackage(Assets $assets)
    {
        $assets->registerScriptUrl('jquery-chosen', 'jQuery/chosen/jquery.chosen.min.js');
        $assets->registerStyleUrl('jquery-chosen', 'jQuery/chosen/chosen.css');
    }

    public function mobilePackage(Assets $assets)
    {
        $mobileAsset = $assets->url('jQuery/jquery.mobile.custom.min.js');
        $ontouchendCode = <<<code
if("ontouchend" in document) document.write("<script src='{$mobileAsset}'>"+"<"+"/script>");
code;
        $assets->registerScriptCode('ontouchend', $ontouchendCode);
    }

    public function tabletreePackage(Assets $assets)
    {
        $assets->registerScriptUrl('tabletree', 'jQuery/tabletree/jquery.tabletree.js');
        $assets->registerStyleUrl('tabletree', 'jQuery/tabletree/jquery.tabletree.css');
    }

    public function colorboxPackage(Assets $assets)
    {
        $assets->registerScriptUrl('colorbox', 'jQuery/colorbox/jquery.colorbox-min.js');
        $assets->registerStyleUrl('colorbox', 'jQuery/colorbox/colorbox.css');
    }

    public function cxselectPackage(Assets $assets)
    {
        $assets->registerScriptUrl('jquery.cxselect', 'jQuery/cxselect/jquery.cxselect.js');
    }

    public static function registerListeners()
    {
        return array(
            'kernel.assets.package#jquery' => 'jqueryPackage',
            'kernel.assets.package#jquery.ui.datepicker' => 'datepickerPackage',
            'kernel.assets.package#jquery.uniform' => 'uniformPackage',
            'kernel.assets.package#jquery.chosen' => 'chosenPackage',
            'kernel.assets.package#jquery.mobile' => 'mobilePackage',
            'kernel.assets.package#jquery.tabletree' => 'tabletreePackage',
            'kernel.assets.package#jquery.colorbox' => 'colorboxPackage',
            'kernel.assets.package#jquery.cxselect' => 'cxselectPackage'
        );
    }
}