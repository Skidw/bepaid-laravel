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

namespace JackWalterSmith\BePaidLaravel\Types;

class CustomerType
{
    public $ip;
    public $email;
    public $first_name;
    public $last_name;
    public $address;
    public $city;
    public $country;
    public $state;
    public $zip;
    public $phone;
    public $birth_date = NULL;
}