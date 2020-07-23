# BePaid Laravel
![bepaid-laravel](https://user-images.githubusercontent.com/16275872/88276989-a28f4a00-cce8-11ea-91d1-d6a44fb9b687.png)

[![Build Status](https://travis-ci.com/Jack-Walter-Smith/bepaid-laravel.svg?branch=master)](https://travis-ci.com/Jack-Walter-Smith/bepaid-laravel)
[![Codecov branch](https://img.shields.io/codecov/c/github/Jack-Walter-Smith/bepaid-laravel/master.svg?style=flat-square)](https://codecov.io/github/Jack-Walter-Smith/bepaid-laravel)

A wrapper of [BeGateway](https://github.com/begateway/begateway-api-php) for Laravel (unofficial)

## Documentation

### Requirements

**PHP** >=7.1

**Laravel** >= 5.7

## Installation
- Install package

`composer require jack-walter-smith/bepaid-laravel`

- Publish config

`php artisan vendor:publish --provider="JackWalterSmith\BePaidLaravel\Providers\BePaidServiceProvider"`

## Usage

### Basics

All you need to do is to create a new DTO and fill out original object with provided values.

Here is a simple example:

```php
<?php

namespace App\Services;

use JackWalterSmith\BePaidLaravel\Refund;

class PaymentService 
{
    /** @var Refund */
    private $refund;

    public function __construct(Refund $refund) {
        $this->refund = $refund;
    }

    public function refund()
    {
        // Create new DTO
        $refundDto = new \JackWalterSmith\BePaidLaravel\Dtos\RefundDto([
            'reason' => 'Purchase returns',
            'parent_uid' => 'payment_uid',
            'money' => [
                'amount' => 333.33,
            ],
        ]);
        
        $response = $this->refund
            ->fill($refundDto)
            ->submit();
        
        // OR even shorter
        // $response = $this->refund->submit($refundDto);

        // ... process the $response
    }
}
```

The table bellow illustrates which object in `BeGateway` equals to object in `BePaid Laravel`.
All of these objects in `BePaid Laravel` package, that listed below, have public field `$operation`,
which gives you access to original object.
This is in case if package features are not enough to reach goal.
You can check original [package](https://github.com/begateway/begateway-api-php) to see all available methods.

| **BeGateway**          | **BePaid Laravel** | **Facade**       | **DTO**                |
|------------------------|--------------------|------------------|------------------------|
| AuthorizationOperation | Authorization      | Authorization    | AuthorizationDto       |
| CardToken              | CardToken          | CardToken        | CardTokenDto           |
| PaymentOperation       | Payment            | Payment          | PaymentDto             |
| GetPaymentToken        | PaymentToken       | PaymentToken     | PaymentTokenDto        |
| Product                | Product            | Product          | ProductDto             |
| QueryByPaymentToken    | Query              | Query            | QueryByPaymentTokenDto |
| QueryByTrackingId      | Query              | Query            | QueryByTrackingIdDto   |
| QueryByUid             | Query              | Query            | QueryByUidDto          |
| RefundOperation        | Refund             | Refund           | RefundDto              |
| CreditOperation        | Credit             | Credit           | CreditDto              |
| CaptureOperation       | ChildTransaction   | ChildTransaction | CaptureDto             |
| VoidOperation          | ChildTransaction   | ChildTransaction | VoidDto                |

A few words about `Query` and `ChildTransaction` objects. They are also have `$operation` public field,
but there are nuances. It depends on which DTO you will pass to `fill()` or `submit()` method. So let's say
you want to query for transaction by uid, in this case you'll create a `new QueryByUidDto([...])`, then `$operation`
field becomes instance of `\BeGateway\QueryByUid`.

### Subscribe to events

`BePaid Laravel` provides preconfigured URLs that can be used in requests. Below is the list of it:

| **Method** | **Path**              | **Name**              | **Middleware**              | **Event**                                                                 |
|------------|-----------------------|-----------------------|-----------------------------|---------------------------------------------------------------------------|
| POST       | /bepaid/notifications | bepaid\.notifications | bepaid\.inject\_basic\_auth | bepaid\.event\.notification\.success \| bepaid\.event\.notification\.fail |
| GET        | /bepaid/success       | bepaid\.success       | \-                          | bepaid\.event\.success                                                    |
| GET        | /bepaid/decline       | bepaid\.decline       | \-                          | bepaid\.event\.fail                                                       |
| GET        | /bepaid/fail          | bepaid\.fail          | \-                          | bepaid\.event\.return                                                     |
| GET        | /bapaid/cancel        | bepaid\.cancel        | \-                          | bepaid\.event\.cancel                                                     |
| GET        | /bepaid/return        | bepaid\.return        | \-                          | bepaid\.event\.decline                                                    |


The most important is `notifications`. `BePaid Laravel` already validates if the incoming request was sent by BePaid.
In success validation scenario it will fire `bepaid.event.notification.success` and `bepaid.event.notification.fail` if something
went wrong.

#### How to handle all this stuff?
`BePaid Laravel` ships with abstract class `BePaidSubscriber` which you need to extend.

Create and register a new Event Subscriber:
```php
<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        //
    ];

    /**
     * The subscriber classes to register.
     *
     * @var array
     */
    protected $subscribe = [
        'App\Listeners\PaymentNotificationEventSubscriber',
    ];
}
```

Now just extend `BePaidSubscriber` and define all required methods. That's it.
```php
<?php

namespace App\Listeners;

use JackWalterSmith\BePaidLaravel\Contracts\BePaidSubscriber;
use Illuminate\Http\Request;

class PaymentNotificationEventSubscriber extends BePaidSubscriber
{
    public function onNotificationSuccess(Request $request)
    {
        // ... process the request
    }

    public function onNotificationFail(Request $request)
    {
        // ... process the request
    }

    public function onSuccess(Request $request)
    {
        // ... process the request
    }

    public function onFail(Request $request)
    {
        // ... process the request
    }

    public function onReturn(Request $request)
    {
        // ... process the request
    }

    public function onCancel(Request $request)
    {
        // ... process the request
    }

    public function onDecline(Request $request)
    {
        // ... process the request
    }
}
```