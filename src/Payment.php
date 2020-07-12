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


use BeGateway\PaymentOperation;
use BeGateway\Response;
use JackWalterSmith\BePaidLaravel\Contracts\IGateway;
use JackWalterSmith\BePaidLaravel\Dtos\PaymentDto;

class Payment implements IGateway
{
    /** @var \BeGateway\PaymentOperation */
    public $transaction;

    public function __construct(PaymentOperation $transaction)
    {
        $this->transaction = $transaction;
    }

    /**
     * @param PaymentDto $data
     *
     * @return \BeGateway\Response
     */
    public function purchase($data): Response
    {
        // TODO: Implement purchase() method.
    }

    public function fill($data): IGateway
    {
        // TODO: Implement fill() method.
    }
}