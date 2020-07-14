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

use BeGateway\{
    AuthorizationOperation,
    CardToken,
    GetPaymentToken,
    PaymentOperation,
    ResponseBase
};
use Illuminate\Support\Str;
use JackWalterSmith\BePaidLaravel\Contracts\IGateway;
use JackWalterSmith\BePaidLaravel\Dtos\{AuthorizationDto, CardTokenDto, PaymentDto, PaymentTokenDto, ProductDto};
use MyCLabs\Enum\Enum;

abstract class GatewayAbstract implements IGateway
{
    const IRREGULAR_METHODS = [
        \BeGateway\Product::class => [
            'setinfinite' => 'setInfiniteState',
            'setimmortal' => 'setImmortalState',
            'setexpiredat' => 'setExpiryDate',
            'settransactiontype' => [
                'payment' => 'setPaymentTransactionType',
                'authorization' => 'setAuthorizationTransactionType',
            ],
            'setvisible' => [
                'phone' => 'setPhoneVisible',
                'address' => 'setAddressVisible',
                'first_name' => 'setFirstNameVisible',
                'last_name' => 'setLastNameVisible',
                'city' => 'setCityVisible',
                'state' => 'setStateVisible',
                'zip' => 'setZipVisible',
                'country' => 'setCountryVisible',
                'email' => 'setEmailVisible',
                'birth_date' => 'setBirthDateVisible',
            ],
        ],
    ];

    /** @var AuthorizationOperation|CardToken|GetPaymentToken|PaymentOperation */
    public $transaction;

    /**
     * @param AuthorizationDto|CardTokenDto|PaymentDto|PaymentTokenDto|ProductDto $data
     *
     * @return \BeGateway\ResponseBase
     */
    public function purchase($data = null): ResponseBase
    {
        if ($data) $this->fill($data);

        return $this->transaction->submit();
    }

    public function fill($data, $object = null): IGateway
    {
        $obj = $object ?? $this->transaction;
        $undefinedMethods = [];

        foreach ((array) $data as $property => $value) {
            if (is_array($value)) {
                $snakeCaseProp = Str::snake($property);
                $this->fill($value, $obj->{$snakeCaseProp});
            } else {
                $formattedProperty = strtolower(str_replace('_', '', $property));
                $method = "set{$formattedProperty}";
                if (method_exists($obj, $method) && ! empty($value)) {
                    $obj->{$method}($value);
                } else {
                    $undefinedMethods[] = [$obj, $method, $value];
                }
            }
        }

        if (!empty($undefinedMethods)) $this->handleUndefinedMethods($undefinedMethods);

        return $this;
    }

    /**
     * @param array $methods
     */
    protected function handleUndefinedMethods(array $methods): void
    {
        foreach ($methods as $method) {
            $className = get_class($method[0]);

            if (
                isset(self::IRREGULAR_METHODS[$className]) &&
                array_key_exists($method[1], self::IRREGULAR_METHODS[$className]) &&
                $method[2] !== null
            ) {
                $function = self::IRREGULAR_METHODS[$className][$method[1]];

                if (! is_array($function) && method_exists($className, $function)) {
                    $this->transaction->{$function}($method[2]);
                } elseif (is_array($function)) {
                    $value = $method[2] instanceof Enum ? $method[2]->getValue() : $method[2];

                    if (isset($function[$value])) {
                        $this->transaction->{$function[$value]}($value);
                    }
                }
            }
        }
    }
}