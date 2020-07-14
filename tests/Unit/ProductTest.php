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
use JackWalterSmith\BePaidLaravel\Dtos\ProductDto;
use JackWalterSmith\BePaidLaravel\Product;
use JackWalterSmith\BePaidLaravel\Providers\BePaidServiceProvider;
use Orchestra\Testbench\TestCase;

class ProductTest extends TestCase
{
    /** @var Product */
    private $product;
    
    private $data = [
        'name' => 'test',
        'description' => 'test description',
        'quantity' => 1,
        'infinite' => false,
        'immortal' => false,
        'transaction_type' => 'payment',
        'money' => [
            'amount' => 111.12,
        ],
        'additional_data' => [
            'receipt' => 'Some dummy text',
        ],
    ];

    protected function setUp(): void
    {
        parent::setUp();

        \Mockery::mock('alias:' . GatewayTransport::class, [
            'submit' => '{
              "id":"prd_770aa9c072595c80",
              "name":"test",
              "description":"test description",
              "currency":"BYN",
              "amount":11112,
              "quantity":1,
              "infinite":false,
              "language":"ru",
              "transaction_type":"payment",
              "created_at":"2020-07-14T09:18:15.032Z",
              "updated_at":"2020-07-14T09:18:15.032Z",
              "test":true,
              "additional_data":{
                "receipt_text":"Some dummy text",
                "contract":null,
                "meta":null
              }
            }',
        ])->makePartial();



        $this->product = $this->app->get('bepaid.product');
    }

    protected function getPackageProviders($app)
    {
        return [BePaidServiceProvider::class];
    }

    protected function getPackageAliases($app)
    {
        return [
            'Product' => \JackWalterSmith\BePaidLaravel\Facades\Product::class,
        ];
    }

    public function testLoadedClass()
    {
        $config = $this->app['config']->get('bepaid');
        /** @var \Illuminate\Routing\UrlGenerator $router */
        $router = $this->app['url'];

        $this->assertEquals($config['test_mode'], $this->product->transaction->getTestMode());
        $this->assertEquals($config['currency'], $this->product->transaction->money->getCurrency());
        $this->assertEquals($config['lang'], $this->product->transaction->getLanguage());
        $this->assertEquals($config['lang'], $this->product->transaction->getLanguage());
        $this->assertEquals($router->route($config['urls']['notifications']['name'], [], true), $this->product->transaction->getNotificationUrl());
        $this->assertEquals($router->route($config['urls']['success']['name'], [], true), $this->product->transaction->getSuccessUrl());
        $this->assertEquals($router->route($config['urls']['fail']['name'], [], true), $this->product->transaction->getFailUrl());
        $this->assertEquals($router->route($config['urls']['return']['name'], [], true), $this->product->transaction->getReturnUrl());
        $this->assertNotNull($this->product->transaction->getExpiryDate());
    }

    public function testFill()
    {
        $productDto = new ProductDto($this->data);

        /** @var Product $result */
        $result = $this->product->fill($productDto);

        $this->assertEquals($this->data['name'], $result->transaction->getName());
        $this->assertEquals($this->data['description'], $result->transaction->getDescription());
        $this->assertEquals($this->data['quantity'], $result->transaction->getQuantity());
        $this->assertEquals($this->data['infinite'], $result->transaction->getInfiniteState());
        $this->assertEquals($this->data['immortal'], $result->transaction->getImmortalState());
        $this->assertEquals($this->data['transaction_type'], $result->transaction->getTransactionType());
        $this->assertEquals($this->data['money']['amount'], $result->transaction->money->getAmount());
        $this->assertEquals($this->data['additional_data']['receipt'], $result->transaction->additional_data->getReceipt());
    }

    public function testPurchaseWithData()
    {
        $productDto = new ProductDto($this->data);

        /** @var \BeGateway\ResponseApiProduct $response */
        $response = $this->product->purchase($productDto);

        /** @var object $result */
        $result = $response->getResponse();

        $this->assertEquals($this->data['name'], $this->product->transaction->getName());
        $this->assertEquals($this->data['description'], $this->product->transaction->getDescription());
        $this->assertEquals($this->data['quantity'], $this->product->transaction->getQuantity());
        $this->assertEquals($this->data['infinite'], $this->product->transaction->getInfiniteState());
        $this->assertEquals($this->data['immortal'], $this->product->transaction->getImmortalState());
        $this->assertEquals($this->data['transaction_type'], $this->product->transaction->getTransactionType());
        $this->assertEquals($this->data['money']['amount'], $this->product->transaction->money->getAmount());
        $this->assertEquals($this->data['additional_data']['receipt'], $this->product->transaction->additional_data->getReceipt());

        $this->assertTrue($response->isValid());
        $this->assertTrue($response->isSuccess());
        $this->assertNotNull($response->getId());

        $this->assertEquals($result->name, $this->product->transaction->getName());
        $this->assertEquals($result->description, $this->product->transaction->getDescription());
        $this->assertEquals($result->quantity, $this->product->transaction->getQuantity());
        $this->assertEquals($result->infinite, $this->product->transaction->getInfiniteState());
        $this->assertEquals($result->language, $this->product->transaction->getLanguage());
        $this->assertEquals($result->transaction_type, $this->product->transaction->getTransactionType());
        $this->assertEquals($result->test, $this->product->transaction->getTestMode());
        $this->assertEquals($result->currency, $this->product->transaction->money->getCurrency());
        $this->assertEquals($result->amount, $this->product->transaction->money->getCents());
    }

    public function testPurchaseWithoutData()
    {
        $productDto = new ProductDto($this->data);

        $this->product->fill($productDto);

        /** @var \BeGateway\ResponseApiProduct $response */
        $response = $this->product->purchase();

        /** @var object $result */
        $result = $response->getResponse();

        $this->assertEquals($this->data['name'], $this->product->transaction->getName());
        $this->assertEquals($this->data['description'], $this->product->transaction->getDescription());
        $this->assertEquals($this->data['quantity'], $this->product->transaction->getQuantity());
        $this->assertEquals($this->data['infinite'], $this->product->transaction->getInfiniteState());
        $this->assertEquals($this->data['immortal'], $this->product->transaction->getImmortalState());
        $this->assertEquals($this->data['transaction_type'], $this->product->transaction->getTransactionType());
        $this->assertEquals($this->data['money']['amount'], $this->product->transaction->money->getAmount());
        $this->assertEquals($this->data['additional_data']['receipt'], $this->product->transaction->additional_data->getReceipt());

        $this->assertTrue($response->isValid());
        $this->assertTrue($response->isSuccess());
        $this->assertNotNull($response->getId());

        $this->assertEquals($result->name, $this->product->transaction->getName());
        $this->assertEquals($result->description, $this->product->transaction->getDescription());
        $this->assertEquals($result->quantity, $this->product->transaction->getQuantity());
        $this->assertEquals($result->infinite, $this->product->transaction->getInfiniteState());
        $this->assertEquals($result->language, $this->product->transaction->getLanguage());
        $this->assertEquals($result->transaction_type, $this->product->transaction->getTransactionType());
        $this->assertEquals($result->test, $this->product->transaction->getTestMode());
        $this->assertEquals($result->currency, $this->product->transaction->money->getCurrency());
        $this->assertEquals($result->amount, $this->product->transaction->money->getCents());
    }
}