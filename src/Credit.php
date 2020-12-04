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

use BeGateway\CreditOperation;
use BeGateway\ResponseBase;
use Illuminate\Support\Str;
use JackWalterSmith\BePaidLaravel\Contracts\IGateway;
use JackWalterSmith\BePaidLaravel\Dtos\CreditDto;

class Credit extends GatewayAbstract
{
    /** @var CreditOperation */
    public $operation;

    public function __construct(CreditOperation $operation)
    {
        $this->operation = $operation;
    }

    /**
     * @param null|CreditDto $data
     *
     * @return \BeGateway\Response
     * @throws \Exception
     */
    public function submit($data = null): ResponseBase
    {
        return parent::submit($data);
    }

    /**
     * @param CreditDto $data
     *
     * @param null      $object
     *
     * @return IGateway
     */
    public function fill($data, $object = null): IGateway
    {
        if ($data instanceof CreditDto && empty($data->tracking_id)) {
            $data->tracking_id = Str::uuid()->toString();
        }

        return parent::fill($data, $object);
    }
}