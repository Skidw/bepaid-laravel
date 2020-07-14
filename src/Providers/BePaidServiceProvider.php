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
use JackWalterSmith\BePaidLaravel\{Authorization,
    CardToken,
    Enums\CurrencyEnum,
    Enums\LanguageEnum,
    Payment,
    PaymentToken,
    Product,
    Query};
use Illuminate\Support\Str;

class BePaidServiceProvider extends ServiceProvider
{
    private const CONFIG_PATH = __DIR__ . '/../../config/bepaid.php';
    private const ROUTES_PATH = __DIR__ . '/../../routes/bepaid.php';

    /**
     * {@inheritDoc}
     */
    public function register()
    {
        $this->mergeConfigFrom(self::CONFIG_PATH, 'bepaid');

        $this->setUp();

        $this->bindPaymentToken();
        $this->bindPayment();
        $this->bindAuthorization();
        $this->bindCardToken();
        $this->bindProduct();
        $this->bindQuery();
    }

    /**
     * {@inheritDoc}
     */
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

    private function setUp(): void
    {
        $config = config('bepaid') ?? require self::CONFIG_PATH;

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

            $transaction->setTestMode($config['test_mode']);
            $transaction->money->setCurrency($this->getCurrency($config));
            $transaction->setLanguage($this->getLanguage($config));
            $transaction->setNotificationUrl(route($config['urls']['notifications']['name'], [], true));
            $transaction->setSuccessUrl(route($config['urls']['success']['name'], [], true));
            $transaction->setDeclineUrl(route($config['urls']['decline']['name'], [], true));
            $transaction->setFailUrl(route($config['urls']['fail']['name'], [], true));
            $transaction->setCancelUrl(route($config['urls']['cancel']['name'], [], true));
            $transaction->setAttempts($config['attempts']);
            $transaction->setExpiryDate(now()->addMinutes($config['expired_at'])->toIso8601String());

            $this->setSpecialProperties($config['visible'], 'visible', $transaction);
            $this->setSpecialProperties($config['read_only'], 'readonly', $transaction);

            return new PaymentToken($transaction);
        });

        $this->app->alias(PaymentToken::class, 'bepaid.paymentToken');
    }

    private function bindPayment(): void
    {
        $this->app->bind(Payment::class, function (Application $app) {
            $config = $app['config']->get('bepaid') ?? require self::CONFIG_PATH;

            $transaction = new PaymentOperation();

            $transaction->setTestMode($config['test_mode']);
            $transaction->money->setCurrency($this->getCurrency($config));
            $transaction->setLanguage($this->getLanguage($config));
            $transaction->setNotificationUrl(route($config['urls']['notifications']['name'], [], true));

            return new Payment($transaction);
        });

        $this->app->alias(Payment::class, 'bepaid.payment');
    }

    private function bindAuthorization(): void
    {
        $this->app->bind(Authorization::class, function (Application $app) {
            $config = $app['config']->get('bepaid') ?? require self::CONFIG_PATH;

            $transaction = new AuthorizationOperation();

            $transaction->setTestMode($config['test_mode']);
            $transaction->money->setCurrency($this->getCurrency($config));
            $transaction->setLanguage($this->getLanguage($config));
            $transaction->setNotificationUrl(route($config['urls']['notifications']['name'], [], true));
            $transaction->setReturnUrl(route($config['urls']['return']['name'], [], true));

            return new Authorization($transaction);
        });

        $this->app->alias(Authorization::class, 'bepaid.authorization');
    }

    private function bindCardToken(): void
    {
        $this->app->bind(CardToken::class, function () {
            return new BePaidCardToken();
        });

        $this->app->alias(CardToken::class, 'bepaid.cardToken');
    }

    private function bindProduct(): void
    {
        $this->app->bind(Product::class, function (Application $app) {
            $config = $app['config']->get('bepaid') ?? require self::CONFIG_PATH;

            $transaction = new BePaidProduct;

            $transaction->setTestMode($config['test_mode']);
            $transaction->money->setCurrency($this->getCurrency($config));
            $transaction->setLanguage($this->getLanguage($config));
            $transaction->setNotificationUrl(route($config['urls']['notifications']['name'], [], true));
            $transaction->setSuccessUrl(route($config['urls']['success']['name'], [], true));
            $transaction->setFailUrl(route($config['urls']['fail']['name'], [], true));
            $transaction->setReturnUrl(route($config['urls']['return']['name'], [], true));
            $transaction->setExpiryDate(now()->addMinutes($config['expired_at'])->toIso8601String());

            $this->setSpecialProperties($config['visible'], 'visible', $transaction);

            return new Product($transaction);
        });

        $this->app->alias(Product::class, 'bepaid.product');
    }

    private function bindQuery(): void
    {
        $this->app->bind(Query::class, function () {
            $queryByPaymentToken = new QueryByPaymentToken();
            $queryByTrackingId = new QueryByTrackingId();
            $queryByUuid = new QueryByUid();

            return new Query($queryByPaymentToken, $queryByTrackingId, $queryByUuid);
        });

        $this->app->alias(Query::class, 'bepaid.query');
    }

    private function getCurrency(?array $conf = null): string
    {
        $config = $conf ?? (config('bepaid') ?? require self::CONFIG_PATH);

        $formattedCurrency = strtoupper($config['currency']);
        $fallbackFormattedCurrency = strtoupper($config['fallback_currency']);

        return CurrencyEnum::isValid($formattedCurrency) ? new CurrencyEnum($formattedCurrency) : new CurrencyEnum($fallbackFormattedCurrency);
    }

    private function getLanguage(?array $conf = null): string
    {
        $config = $conf ?? (config('bepaid') ?? require self::CONFIG_PATH);

        $formattedLanguage = strtolower($config['lang']);
        $fallbackFormattedLanguage = strtolower($config['fallback_lang']);

        return LanguageEnum::isValid($formattedLanguage) ? new LanguageEnum($formattedLanguage) : new LanguageEnum($fallbackFormattedLanguage);
    }

    /**
     * Set visible and readonly properties.
     *
     * @param array $properties
     * @param       $trx
     */
    private function setSpecialProperties(array $properties, $key, $trx): void {
        foreach ($properties as $prop) {
            $camelCaseProp = Str::camel($prop);
            $method = "set{$camelCaseProp}{$key}";
            if (method_exists($trx, $method)) {
                $trx->{$method}();
            }
        }
    }
}