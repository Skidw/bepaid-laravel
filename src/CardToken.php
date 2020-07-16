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

use BeGateway\CardToken as BePaidCardToken;
use BeGateway\ResponseBase;
use JackWalterSmith\BePaidLaravel\Contracts\IGateway;
use JackWalterSmith\BePaidLaravel\Dtos\CardTokenDto;

class CardToken extends GatewayAbstract
{
    /** @var \BeGateway\CardToken */
    public $operation;

    public function __construct(BePaidCardToken $token)
    {
        $this->operation = $token;
    }

    /**
     * @param CardTokenDto $data
     *
     * @return \BeGateway\ResponseCardToken
     * @throws \Exception
     */
    public function submit($data = null): ResponseBase
    {
        return parent::submit($data);
    }

    /**
     * @param CardTokenDto                                                                                   $data
     * @param null|\BeGateway\Money|\BeGateway\AdditionalData|\BeGateway\Customer|\BeGateway\GetPaymentToken $object
     *
     * @return \JackWalterSmith\BePaidLaravel\Contracts\IGateway
     */
    public function fill($data, $object = null): IGateway
    {
        return parent::fill($data, $object);
    }
}