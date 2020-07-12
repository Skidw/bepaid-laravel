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
    QueryByPaymentToken,
    QueryByTrackingId,
    QueryByUid
};

class Query
{
    /** @var \BeGateway\QueryByPaymentToken */
    public $queryByPaymentToken;

    /** @var \BeGateway\QueryByTrackingId */
    public $queryByTrackingId;

    /** @var \BeGateway\QueryByUid */
    public $queryByUuid;

    public function __construct(
        QueryByPaymentToken $queryByPaymentToken,
        QueryByTrackingId $queryByTrackingId,
        QueryByUid $queryByUid
    ) {
        $this->queryByPaymentToken = $queryByPaymentToken;
        $this->queryByTrackingId = $queryByTrackingId;
        $this->queryByUuid = $queryByUid;
    }
}