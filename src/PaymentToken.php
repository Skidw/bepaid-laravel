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

namespace JackWalterSmith\BePaidLaravel;

use BeGateway\{GetPaymentToken, Response};
use JackWalterSmith\BePaidLaravel\Contracts\IGateway;
use JackWalterSmith\BePaidLaravel\Dtos\PaymentTokenDto;

class PaymentToken implements IGateway
{
    /** @var \BeGateway\GetPaymentToken */
    public $transaction;

    public function __construct(GetPaymentToken $transaction)
    {
        $this->transaction = $transaction;
    }

    /**
     * @param PaymentTokenDto $data
     *
     * @return \BeGateway\Response
     */
    public function purchase($data): Response
    {
        if (!empty($data)) $this->fill($data);

        return $this->transaction->submit();
    }

    public function fill($data): IGateway
    {

    }
}