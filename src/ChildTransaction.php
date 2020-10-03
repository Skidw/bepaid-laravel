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

use BeGateway\{Response, ResponseBase};
use BeGateway\CaptureOperation;
use BeGateway\VoidOperation;
use JackWalterSmith\BePaidLaravel\Contracts\IGateway;
use JackWalterSmith\BePaidLaravel\Dtos\{CaptureDto, VoidDto};

class ChildTransaction extends GatewayAbstract
{
    public $operation;
    /** @var VoidOperation */
    private $void;
    /** @var CaptureOperation */
    private $capture;

    public function __construct(CaptureOperation $capture, VoidOperation $void)
    {
        $this->capture = $capture;
        $this->void = $void;
    }

    /**
     * @param VoidDto|CaptureDto $data
     *
     * @return Response
     * @throws \Exception
     */
    public function submit($data = null): ResponseBase
    {
        return parent::submit($data);
    }

    /**
     * @param VoidDto|CaptureDto                                   $data
     * @param null|\BeGateway\Money|VoidOperation|CaptureOperation $object
     *
     * @return \JackWalterSmith\BePaidLaravel\Contracts\IGateway
     */
    public function fill($data, $object = null): IGateway
    {
        switch (get_class($data)) {
            case VoidDto::class:
                $this->operation = $this->void;
                break;
            case CaptureDto::class:
                $this->operation = $this->capture;
                break;
        }

        return parent::fill($data, $object);
    }
}