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

return [
    'gateway_base_url' => env('BEPAID_GATEWAY_BASE_URL', 'https://demo-gateway.begateway.com'),
    'checkout_base_url' => env('BEPAID_CHECKOUT_BASE_URL', 'https://checkout.begateway.com'),
    'api_base_url' => env('BEPAID_API_BASE_URL', 'https://api.begateway.com'),
    'shop_id' => (int)env('BEPAID_SHOP_ID', 361),
    'shop_key' => env('BEPAID_SHOP_KEY', 'b8647b68898b084b836474ed8d61ffe117c9a01168d867f24953b776ddcb134d'),
    'test_mode' => env('APP_ENV') !== 'production',
    'currency' => 'BYN',
    'fallback_currency' => 'USD',
    'lang' => 'ru',
    'fallback_lang' => 'en',
    'urls' => [
        'notifications' => [
            'path' => '/notifications',
            'name' => 'notifications',
        ],
        'success' => [
            'path' => '/success',
            'name' => 'success',
        ],
        'decline' => [
            'path' => '/decline',
            'name' => 'decline',
        ],
        'fail' => [
            'path' => '/fail',
            'name' => 'fail',
        ],
        'cancel' => [
            'path' => '/cancel',
            'name' => 'cancel',
        ],
        'return' => [
            'path' => '/return',
            'name' => 'return'
        ],
    ],
    'visible' => [
        'first_name',
        'last_name',
    ],
    'read_only' => [
        'email',
    ],
    'attempts' => 1,
    'expired_at' => 24 * 60,
];