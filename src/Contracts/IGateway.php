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

namespace JackWalterSmith\BePaidLaravel\Contracts;

use BeGateway\{AuthorizationOperation, CardToken, GetPaymentToken, PaymentOperation, ResponseBase};
use JackWalterSmith\BePaidLaravel\Dtos\{AuthorizationDto,
    CardTokenDto,
    CreditDto,
    PaymentDto,
    PaymentTokenDto,
    ProductDto,
    RefundDto};

interface IGateway
{
    /**
     * @param AuthorizationDto|CardTokenDto|PaymentDto|PaymentTokenDto|ProductDto|RefundDto|CreditDto $data
     *
     * @return \BeGateway\ResponseBase
     */
    public function submit($data = null): ResponseBase;

    /**
     * @param AuthorizationDto|CardTokenDto|PaymentDto|PaymentTokenDto|ProductDto|RefundDto    $data
     * @param null|AuthorizationOperation|CardToken|GetPaymentToken|PaymentOperation|RefundDto $object
     *
     * @return IGateway
     */
    public function fill($data, $object = null): self;
}