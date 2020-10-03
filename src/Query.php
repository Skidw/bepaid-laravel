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

use BeGateway\{QueryByPaymentToken, QueryByTrackingId, QueryByUid};
use JackWalterSmith\BePaidLaravel\Contracts\IGateway;
use JackWalterSmith\BePaidLaravel\Dtos\{QueryByPaymentTokenDto, QueryByTrackingIdDto, QueryByUidDto};

class Query extends GatewayAbstract
{
    /** @var QueryByPaymentToken|QueryByTrackingId|QueryByUid */
    public $operation;
    /** @var \BeGateway\QueryByPaymentToken */
    private $queryByPaymentToken;
    /** @var \BeGateway\QueryByTrackingId */
    private $queryByTrackingId;
    /** @var \BeGateway\QueryByUid */
    private $queryByUuid;

    public function __construct(
        QueryByPaymentToken $queryByPaymentToken,
        QueryByTrackingId $queryByTrackingId,
        QueryByUid $queryByUid
    )
    {
        $this->queryByPaymentToken = $queryByPaymentToken;
        $this->queryByTrackingId = $queryByTrackingId;
        $this->queryByUuid = $queryByUid;
    }

    /**
     * @param QueryByPaymentTokenDto|QueryByTrackingIdDto|QueryByUidDto          $data
     * @param null|\BeGateway\Money|\BeGateway\AdditionalData|\BeGateway\Product $object
     *
     * @return \JackWalterSmith\BePaidLaravel\Contracts\IGateway
     */
    public function fill($data, $object = null): IGateway
    {
        switch (get_class($data)) {
            case QueryByPaymentTokenDto::class:
                $this->operation = $this->queryByPaymentToken;
                break;
            case QueryByTrackingIdDto::class:
                $this->operation = $this->queryByTrackingId;
                break;
            case QueryByUidDto::class:
                $this->operation = $this->queryByUuid;
                break;
        }

        return parent::fill($data, $object);
    }
}