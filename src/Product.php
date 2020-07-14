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

use BeGateway\Product as BePaidProduct;
use BeGateway\ResponseBase;
use JackWalterSmith\BePaidLaravel\Contracts\IGateway;

class Product extends GatewayAbstract
{
    /** @var \BeGateway\Product */
    public $transaction;

    public function __construct(BePaidProduct $transaction)
    {
        $this->transaction = $transaction;
    }

    /**
     * @param \JackWalterSmith\BePaidLaravel\Dtos\ProductDto $data
     *
     * @return \BeGateway\ResponseApiProduct
     */
    public function purchase($data = null): ResponseBase
    {
        return parent::purchase($data);
    }

    /**
     * @param \JackWalterSmith\BePaidLaravel\Dtos\ProductDto $data
     * @param null|\BeGateway\Money|\BeGateway\AdditionalData|\BeGateway\Product $object
     *
     * @return \JackWalterSmith\BePaidLaravel\Contracts\IGateway
     */
    public function fill($data, $object = null): IGateway
    {
        return parent::fill($data, $object);
    }
}