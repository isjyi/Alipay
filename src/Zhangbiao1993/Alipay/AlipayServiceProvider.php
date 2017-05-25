<?php
namespace Zhangbiao1993\Alipay;

use Illuminate\Support\ServiceProvider;
use Illuminate\Foundation\Application as LaravelApplication;
use Laravel\Lumen\Application as LumenApplication;

class AlipayServiceProvider extends ServiceProvider
{

    /**
     * boot process
     */
    public function boot()
    {
        $this->setupConfig();
    }

    /**
     * Setup the config.
     *
     * @return void
     */
    protected function setupConfig()
    {
        $source_config = realpath(__DIR__ . '/../../config/config.php');
        $source_mobile = realpath(__DIR__ . '/../../config/mobile.php');
        $source_web = realpath(__DIR__ . '/../../config/web.php');
        if ($this->app instanceof LaravelApplication && $this->app->runningInConsole()) {
            $this->publishes([
                $source_config => config_path('zhangbiao-alipay.php'),
                $source_mobile => config_path('zhangbiao-alipay-mobile.php'),
                $source_web => config_path('zhangbiao-alipay-web.php'),
            ]);
        } elseif ($this->app instanceof LumenApplication) {
            $this->app->configure('zhangbiao-alipay');
            $this->app->configure('zhangbiao-alipay-mobile');
            $this->app->configure('zhangbiao-alipay-web');
        }
        
        $this->mergeConfigFrom($source_config, 'zhangbiao-alipay');
        $this->mergeConfigFrom($source_mobile, 'zhangbiao-alipay-mobile');
        $this->mergeConfigFrom($source_web, 'zhangbiao-alipay-web');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        
        $this->app->bind('alipay.mobile', function ($app) {
            $alipay = new Mobile\SdkPayment();

            $alipay->setPartner($app->config->get('zhangbiao-alipay.partner_id'))
                ->setSellerId($app->config->get('zhangbiao-alipay.seller_id'))
                ->setSignType($app->config->get('zhangbiao-alipay-mobile.sign_type'))
                ->setPrivateKeyPath($app->config->get('zhangbiao-alipay-mobile.private_key_path'))
                ->setPublicKeyPath($app->config->get('zhangbiao-alipay-mobile.public_key_path'))
                ->setNotifyUrl($app->config->get('zhangbiao-alipay-mobile.notify_url'));

            return $alipay;
        });

        $this->app->bind('alipay.web', function ($app) {
            $alipay = new Web\SdkPayment();

            $alipay->setPartner($app->config->get('zhangbiao-alipay.partner_id'))
                ->setSellerId($app->config->get('zhangbiao-alipay.seller_id'))
                ->setKey($app->config->get('zhangbiao-alipay-web.key'))
                ->setSignType($app->config->get('zhangbiao-alipay-web.sign_type'))
                ->setNotifyUrl($app->config->get('zhangbiao-alipay-web.notify_url'))
                ->setReturnUrl($app->config->get('zhangbiao-alipay-web.return_url'))
                ->setExterInvokeIp($app->request->getClientIp());

            return $alipay;
        });

        $this->app->bind('alipay.wap', function ($app) {
            $alipay = new Wap\SdkPayment();

            $alipay->setPartner($app->config->get('zhangbiao-alipay.partner_id'))
            ->setSellerId($app->config->get('zhangbiao-alipay.seller_id'))
            ->setKey($app->config->get('zhangbiao-alipay-web.key'))
            ->setSignType($app->config->get('zhangbiao-alipay-web.sign_type'))
            ->setNotifyUrl($app->config->get('zhangbiao-alipay-web.notify_url'))
            ->setReturnUrl($app->config->get('zhangbiao-alipay-web.return_url'))
            ->setExterInvokeIp($app->request->getClientIp());

            return $alipay;
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [
            'alipay.mobile',
            'alipay.web',
            'alipay.wap',
        ];
    }
}
