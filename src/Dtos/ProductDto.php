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

namespace JackWalterSmith\BePaidLaravel\Dtos;

class ProductDto
{
    /** @var \JackWalterSmith\BePaidLaravel\Types\MoneyType */
    public $money;

    /** @var \JackWalterSmith\BePaidLaravel\Types\AdditionalDataType */
    public $additionalData;

    /** @var string */
    public $name;

    /** @var string */
    public $description;

    /** @var int */
    public $quantity;

    /** @var bool */
    public $infinite;

    /** @var bool */
    public $immortal;

    /** @var \JackWalterSmith\BePaidLaravel\Enums\TransactionTypesEnum */
    public $transactionType;

    /** @var array<string> */
    public $visible;

    /** @var string */
    public $expiredAt;
}