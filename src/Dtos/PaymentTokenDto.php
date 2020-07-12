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

class PaymentTokenDto
{
    /** @var \JackWalterSmith\BePaidLaravel\Types\CustomerType */
    public $customer;

    /** @var \JackWalterSmith\BePaidLaravel\Types\MoneyType */
    public $money;

    /** @var \JackWalterSmith\BePaidLaravel\Types\AdditionalDataType */
    public $additionalData;

    /** @var string */
    public $description;

    /** @var string */
    public $trackingId;

    /** @var \JackWalterSmith\BePaidLaravel\Enums\TransactionTypesEnum */
    public $transactionType;

    /** @var array<string> */
    public $readonly;

    /** @var array<string> */
    public $visible;

    /** @var array<\BeGateway\PaymentMethod\Base> */
    public $paymentMethods;

    /** @var string */
    public $expiredAt;

    /** @var int */
    public $attempts;
}