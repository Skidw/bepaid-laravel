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

use BeGateway\RefundOperation;
use BeGateway\ResponseBase;
use JackWalterSmith\BePaidLaravel\Contracts\IGateway;
use JackWalterSmith\BePaidLaravel\Dtos\RefundDto;

/**
 * Class Refund
 *
 * @package JackWalterSmith\BePaidLaravel
 */
class Refund extends GatewayAbstract
{
    /**
     * @var \BeGateway\RefundOperation
     */
    public $operation;

    /**
     * Refund constructor.
     *
     * @param \BeGateway\RefundOperation $operation
     */
    public function __construct(RefundOperation $operation)
    {
        $this->operation = $operation;
    }

    /**
     * @param null $data
     *
     * @return \BeGateway\ResponseBase
     * @throws \Exception
     */
    public function submit($data = null): ResponseBase
    {
        return parent::submit($data);
    }

    /**
     * @param RefundDto                                                                                     $data
     * @param null|\BeGateway\Money|\BeGateway\AdditionalData|\BeGateway\Product|\BeGateway\RefundOperation $object
     *
     * @return \JackWalterSmith\BePaidLaravel\Contracts\IGateway
     */
    public function fill($data, $object = null): IGateway
    {
        return parent::fill($data, $object);
    }
}