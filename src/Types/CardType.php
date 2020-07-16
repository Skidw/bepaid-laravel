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
    public $card_number;
    public $card_holder;
    public $card_exp_month;
    public $card_exp_year;
    public $card_cvc;
    public $first_one;
    public $last_four;
    public $brand;
    public $card_token = null;
    public $card_skip_three_d_secure = false;
    public $encrypted = false;
}