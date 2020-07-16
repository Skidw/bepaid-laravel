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

namespace JackWalterSmith\BePaidLaravel\Tests\Unit;

use BeGateway\GatewayTransport;
use BeGateway\PaymentMethod\CreditCard;
use JackWalterSmith\BePaidLaravel\Dtos\PaymentTokenDto;
use JackWalterSmith\BePaidLaravel\PaymentToken;
use JackWalterSmith\BePaidLaravel\Providers\BePaidServiceProvider;
use Orchestra\Testbench\TestCase;

class PaymentTokenTest extends TestCase
{
    /** @var PaymentToken */
    private $paymentToken;
    /** @var */
    private $data = [];

    protected function setUp(): void
    {
        parent::setUp();

        \Mockery::mock('alias:' . GatewayTransport::class, [
            'submit' => '{
              "checkout":{
                "token":"2d579c5625da92b088f12d41c0c7548472e7a5f4477c4d579ca8976a53ecf6d2",
                "redirect_url":"https:\/\/checkout.begateway.com\/v2\/checkout?token=2d579c5625da92b088f12d41c0c7548472e7a5f4477c4d579ca8976a53ecf6d2"
              }
            }',
        ])->makePartial();

        $this->paymentToken = $this->app->get('bepaid.paymentToken');

        $this->data = [
            'customer' => [
                'ip' => '127.0.0.1',
                'email' => 'test@example.com',
                'first_name' => 'John',
                'last_name' => 'Doe',
                'address' => '4876 Gallegos Vista Apt. 382\nLake Christine, VA 92929',
                'city' => 'Minsk',
                'country' => 'BY',
                'state' => 'Minsk',
                'zip' => '220100',
                'phone' => '+375291234567',
                'birth_date' => '1970-01-01',
            ],
            'money' => [
                'amount' => 222.22,
            ],
            'additional_data' => [
                'receipt' => ['Some text'],
            ],
            'readonly' => ['first_name', 'last_name'],
            'visible' => ['email'],
            'payment_methods' => [new CreditCard()],
            'description' => 'Dummy text',
            'tracking_id' => 'test_tracking_id_1234',
            'transaction_type' => 'payment',
            'expired_at' => date(DATE_ISO8601, strtotime('+2 days')),
            'attempts' => 4,
        ];
    }

    protected function getPackageProviders($app)
    {
        return [BePaidServiceProvider::class];
    }

    public function testLoadedClass()
    {
        $config = $this->app['config']->get('bepaid');

        /** @var \Illuminate\Routing\UrlGenerator $router */
        $router = $this->app['url'];

        $this->assertEquals($config['test_mode'], $this->paymentToken->transaction->getTestMode());
        $this->assertEquals($config['currency'], $this->paymentToken->transaction->money->getCurrency());
        $this->assertEquals($config['lang'], $this->paymentToken->transaction->getLanguage());
        $this->assertEquals($router->route($config['urls']['notifications']['name'], [], true), $this->paymentToken->transaction->getNotificationUrl());
        $this->assertEquals($router->route($config['urls']['success']['name'], [], true), $this->paymentToken->transaction->getSuccessUrl());
        $this->assertEquals($router->route($config['urls']['fail']['name'], [], true), $this->paymentToken->transaction->getFailUrl());
        $this->assertEquals($router->route($config['urls']['decline']['name'], [], true), $this->paymentToken->transaction->getDeclineUrl());
        $this->assertEquals($router->route($config['urls']['cancel']['name'], [], true), $this->paymentToken->transaction->getCancelUrl());
        $this->assertNotNull($this->paymentToken->transaction->getExpiredAt());
        $this->assertEquals($config['attempts'], $this->paymentToken->transaction->getAttempts());
        $this->assertEquals($config['visible'], $this->paymentToken->transaction->getVisible());
        $this->assertEquals($config['read_only'], $this->paymentToken->transaction->getReadonly());
    }

    public function testFill()
    {
        $paymentTokenDto = new PaymentTokenDto($this->data);

        /** @var PaymentToken $result */
        $result = $this->paymentToken->fill($paymentTokenDto);

        $this->assertEquals($this->data['description'], $result->transaction->getDescription());
        $this->assertEquals($this->data['tracking_id'], $result->transaction->getTrackingId());
        $this->assertEquals($this->data['transaction_type'], $result->transaction->getTransactionType());
        $this->assertEquals($this->data['money']['amount'], $result->transaction->money->getAmount());
        $this->assertEquals($this->data['additional_data']['receipt'], $result->transaction->additional_data->getReceipt());
        $this->assertEquals($this->data['expired_at'], $result->transaction->getExpiredAt());
        $this->assertEquals($this->data['attempts'], $result->transaction->getAttempts());
        $visible = $result->transaction->getVisible();
        $this->assertEquals(sort($this->data['visible']), sort($visible));
        $readonly = $result->transaction->getReadonly();
        $this->assertEquals(sort($this->data['readonly']), sort($readonly));
        $this->assertEquals(count($this->data['customer']), count((array)$result->transaction->customer));

        foreach ($result->transaction->customer as $key => $value) {
            $this->assertEquals($this->data['customer'][$key], $value);
        }
    }

    public function testPurchaseWithData()
    {
        $paymentTokenDto = new PaymentTokenDto($this->data);

        /** @var \BeGateway\ResponseCheckout $response */
        $response = $this->paymentToken->submit($paymentTokenDto);

        $this->assertTrue($response->isValid());
        $this->assertTrue($response->isSuccess());
        $this->assertFalse($response->isError());
        $this->assertIsString($response->getRedirectUrl());
        $this->assertIsString($response->getToken());
    }

    public function testPurchaseWithoutData()
    {
        $paymentTokenDto = new PaymentTokenDto($this->data);

        /** @var \BeGateway\ResponseCheckout $response */
        $response = $this->paymentToken->fill($paymentTokenDto)->submit();

        $this->assertTrue($response->isValid());
        $this->assertTrue($response->isSuccess());
        $this->assertFalse($response->isError());
        $this->assertIsString($response->getRedirectUrl());
        $this->assertIsString($response->getToken());
    }
}