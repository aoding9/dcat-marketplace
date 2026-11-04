<?php

namespace Sparkinzy\DcatMarketPlace;

use Dcat\Admin\Admin;
use Dcat\Admin\Extend\ServiceProvider;
use Illuminate\Support\Str;

class DcatMarketplaceServiceProvider extends ServiceProvider
{
    protected $js = [
    ];
    protected $css = [
    ];

    public function register()
    {
        //

    }

    private function initMarketPlace()
    {
        $current_url = request()->getUri();
        if (Str::endsWith($current_url, '/auth/extensions')){
            $setting = admin_setting_array('sparkinzy:dcat-marketplace');
            $path = $setting['path'] ?? 'dcat-marketplace';
            $marketplaceUrl = admin_url($path);
            Admin::script(<<<JS
(function () {
    var url = '{$marketplaceUrl}';
    function go(e) {
        e.preventDefault();
        e.stopPropagation();
        window.location.href = url;
    }
    $('.icon-shopping-cart').parent().off('click.dcatMarketplace').on('click.dcatMarketplace', go);
    $('button, a, .btn').filter(function () {
        return $.trim($(this).text()) === '应用市场' || $(this).find('.icon-shopping-cart').length;
    }).off('click.dcatMarketplace').on('click.dcatMarketplace', go);
})();
JS
            );

        }
    }

    public function init()
    {
        parent::init();
        $this->initMarketPlace();

        //
    }

    public function settingForm()
    {
        return new Setting($this);
    }
}
