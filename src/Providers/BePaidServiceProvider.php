<?php

/**
 * This file is part of bepaid-laravel package.
 *
 * @package  BePaid Laravel
 * @category BePaid Laravel
 * @author   Nikita Kim <n.a.kim@yandex.ru>
 * @link     https://github.com/Jack-Walter-Smith/bepaid-laravel
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */


namespace JackWalterSmith\BePaidLaravel\Providers;


use BeGateway\{AuthorizationOperation,
    CardToken as BePaidCardToken,
    GetPaymentToken,
    PaymentOperation,
    Product as BePaidProduct,
    QueryByPaymentToken,
    QueryByTrackingId,
    QueryByUid,
    Settings
};
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use JackWalterSmith\BePaidLaravel\{Authorization, CardToken, Payment, PaymentToken, Product, Query};
use JackWalterSmith\BePaidLaravel\Facades\PaymentToken as PaymentTokenFacade;

class BePaidServiceProvider extends ServiceProvider
{
    private const CONFIG_PATH = __DIR__ . '/../../config/bepaid.php';
    private const ROUTES_PATH = __DIR__ . '/../../routes/bepaid.php';

    public function register()
    {
        $this->mergeConfigFrom(self::CONFIG_PATH, 'bepaid');

        $config = config('bepaid') ?? require self::CONFIG_PATH;

        $this->setUp($config);

        $this->bindPaymentToken();
        $this->bindPayment();
        $this->bindAuthorization();
        $this->bindCardToken();
        $this->bindProduct();
    }

    public function boot(): void
    {
        $this->bootConfig();
        $this->bootRoutes();
    }

    /**
     * Register config.
     */
    private function bootConfig(): void
    {
        $this->publishes([
            self::CONFIG_PATH => config_path('bepaid.php'),
        ], 'bepaid');
    }

    /**
     * Register routes.
     */
    private function bootRoutes(): void
    {
        $this->loadRoutesFrom(self::ROUTES_PATH);
    }

    private function setUp(array $config): void
    {
        Settings::$shopId = $config['shop_id'];
        Settings::$shopKey = $config['shop_key'];
        Settings::$gatewayBase = $config['gateway_base_url'];
        Settings::$checkoutBase = $config['checkout_base_url'];
        Settings::$apiBase = $config['api_base_url'];
    }

    private function bindPaymentToken(): void
    {
        $this->app->bind(PaymentToken::class, function ($app) {
            $config = $app['config']->get('bepaid') ?? require self::CONFIG_PATH;

            $transaction = new GetPaymentToken();

            $transaction->setTestMode($config['testing_mode']);
            $transaction->money->setCurrency($config['currency']);
            $transaction->setLanguage($config['language']);
            $transaction->setNotificationUrl(route($config['urls']['notifications']['name'], [], true));
            $transaction->setSuccessUrl(route($config['urls']['success']['name'], [], true));
            $transaction->setDeclineUrl(route($config['urls']['decline']['name'], [], true));
            $transaction->setFailUrl(route($config['urls']['fail']['name'], [], true));
            $transaction->setCancelUrl(route($config['urls']['cancel']['name'], [], true));

            return new PaymentToken($transaction);
        });

        $this->app->alias(PaymentToken::class, PaymentTokenFacade::class);
    }

    private function bindPayment(): void
    {
        $this->app->bind(Payment::class, function (Application $app) {
            $config = $app['config']->get('bepaid') ?? require self::CONFIG_PATH;

            $transaction = new PaymentOperation();

            $transaction->setTestMode($config['testing_mode']);
            $transaction->money->setCurrency($config['currency']);
            $transaction->setLanguage($config['language']);
            $transaction->setNotificationUrl(route($config['urls']['notifications']['name'], [], true));

            return new Payment($transaction);
        });

        // TODO: add facade
    }

    private function bindAuthorization(): void
    {
        $this->app->bind(Authorization::class, function (Application $app) {
            $config = $app['config']->get('bepaid') ?? require self::CONFIG_PATH;

            $transaction = new AuthorizationOperation();

            $transaction->setTestMode($config['testing_mode']);
            $transaction->money->setCurrency($config['currency']);
            $transaction->setLanguage($config['language']);
            $transaction->setNotificationUrl(route($config['urls']['notifications']['name'], [], true));

            return new Authorization($transaction);
        });

        // TODO: add facade
    }

    private function bindCardToken(): void
    {
        $this->app->bind(CardToken::class, function () {
            return new BePaidCardToken();
        });

        // TODO: add facade
    }

    private function bindProduct(): void
    {
        $this->app->bind(Product::class, function (Application $app) {
            $transaction = new BePaidProduct;

            $currency = $app['config']->get('bepaid.currency');
            $transaction->money->setCurrency($currency);

            return new Product($transaction);
        });

        // TODO: add facade
    }

    private function bindQuery(): void
    {
        $this->app->bind(Query::class, function () {
            $queryByPaymentToken = new QueryByPaymentToken();
            $queryByTrackingId = new QueryByTrackingId();
            $queryByUuid = new QueryByUid();

            return new Query($queryByPaymentToken, $queryByTrackingId, $queryByUuid);
        });

        // TODO: add facade
    }
}