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

use BeGateway\{AuthorizationOperation, CardToken, GetPaymentToken, PaymentOperation, RefundOperation, ResponseBase};
use Illuminate\Support\Str;
use JackWalterSmith\BePaidLaravel\Contracts\IGateway;
use JackWalterSmith\BePaidLaravel\Dtos\{AuthorizationDto,
    CaptureDto,
    CardTokenDto,
    CreditDto,
    PaymentDto,
    PaymentTokenDto,
    ProductDto,
    QueryByPaymentTokenDto,
    QueryByTrackingIdDto,
    QueryByUidDto,
    RefundDto,
    VoidDto};
use JackWalterSmith\BePaidLaravel\Exceptions\BadRequestException;
use JackWalterSmith\BePaidLaravel\Exceptions\TransactionException;

abstract class GatewayAbstract implements IGateway
{
    /** @var AuthorizationOperation|CardToken|GetPaymentToken|PaymentOperation|RefundOperation */
    public $operation;

    /**
     * @param AuthorizationDto|CardTokenDto|PaymentDto|PaymentTokenDto|ProductDto|RefundDto|QueryByPaymentTokenDto|QueryByTrackingIdDto|QueryByUidDto|CreditDto|VoidDto|CaptureDto $data
     *
     * @return \BeGateway\ResponseBase
     * @throws \Exception
     */
    public function submit($data = null): ResponseBase
    {
        if ($data) $this->fill($data);

        $response = json_decode($this->operation->submit());

        if (isset($response->errors)) {
            if (strpos($response->message, 'transaction can\'t be refunded')) {
                throw new TransactionException($response->message, $response->errors);
            } elseif (
                strpos($response->message, 'can\'t be blank') ||
                strpos($response->message, 'is invalid') ||
                strpos($response->message, 'is not a number') ||
                strpos($response->message, 'must be greater than 0')
            ) {
                throw new BadRequestException($response->message, $response->errors);
            } else {
                throw new \Exception($response->message);
            }
        }

        return $response;
    }

    /**
     * @param AuthorizationDto|CardTokenDto|PaymentDto|PaymentTokenDto|ProductDto|RefundDto|QueryByPaymentTokenDto|QueryByTrackingIdDto|QueryByUidDto|CreditDto|VoidDto|CaptureDto $data
     * @param null                                                                                                                                                                 $object
     *
     * @return \JackWalterSmith\BePaidLaravel\Contracts\IGateway
     */
    public function fill($data, $object = null): IGateway
    {
        $obj = $object ?? $this->operation;

        foreach ($data as $property => $value) {
            if ($value !== null) {
                if (is_object($value)) {
                    $snakeCaseProp = Str::snake($property);
                    $this->fill($value, $obj->{$snakeCaseProp});
                } else {
                    $formattedProperty = strtolower(str_replace('_', '', $property));
                    $method = "set{$formattedProperty}";
                    if (method_exists($obj, $method)) {
                        $obj->{$method}($value);
                    }
                }
            }
        }

        return $this;
    }
}