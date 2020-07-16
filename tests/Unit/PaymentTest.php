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

use Orchestra\Testbench\TestCase;

class PaymentTest extends TestCase
{
    /** @var \JackWalterSmith\BePaidLaravel\Payment */
    private $payment;

    private $data = [
        'money' => [
            'amount' => 333.33,
        ],
        'description' => 'Test desc',
        'tracking_id' => 'test_tracking_id_1234',
        'card' => [
            'card_number' => '4200000000000000',
            'card_holder' => 'JOHN DOE',
            'card_exp_month' => 1,
            'card_exp_year' => 2030,
            'card_cvc' => '123',
        ],
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
        'additional_data' => [
            'receipt' => ['Some text'],
        ],
    ];
}
