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

use BeGateway\{GetPaymentToken, ResponseBase};
use Illuminate\Support\Str;
use JackWalterSmith\BePaidLaravel\Contracts\IGateway;
use JackWalterSmith\BePaidLaravel\Dtos\PaymentTokenDto;

class PaymentToken extends GatewayAbstract
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
     * @return \BeGateway\ResponseCheckout
     */
    public function submit($data = null): ResponseBase
    {
        return parent::submit($data);
    }

    /**
     * @param PaymentTokenDto                                                                                $data
     * @param null|\BeGateway\Money|\BeGateway\AdditionalData|\BeGateway\Customer|\BeGateway\GetPaymentToken $object
     *
     * @return \JackWalterSmith\BePaidLaravel\Contracts\IGateway
     */
    public function fill($data, $object = null): IGateway
    {
        if ($data instanceof PaymentTokenDto && empty($data->tracking_id)) {
            $data->tracking_id = Str::uuid();
        }

        return parent::fill($data, $object);
    }
}