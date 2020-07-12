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

class CardType
{
    public $cardNumber;
    public $cardHolder;
    public $cardExpMonth;
    public $cardExpYear;
    public $cardCvc;
    public $first_1; // TODO: change to valid method
    public $last_4; // TODO: change to valid method
    public $brand;
    public $cardToken = null;
    public $cardSkipThreedSecure = false; // TODO: change to valid method
    public $isEncrypted = false;  // TODO: change to valid method
}