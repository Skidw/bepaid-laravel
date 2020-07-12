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


namespace JackWalterSmith\BePaidLaravel\Facades;


use Illuminate\Support\Facades\Facade;

class PaymentToken extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'bepaid.paymentToken';
    }
}