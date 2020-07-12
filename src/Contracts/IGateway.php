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

use BeGateway\Response;
use JackWalterSmith\BePaidLaravel\Dtos\{AuthorizationDto, CardTokenDto, PaymentDto, PaymentTokenDto, ProductDto};

interface IGateway
{
    /**
     * @param AuthorizationDto|CardTokenDto|PaymentDto|PaymentTokenDto|ProductDto $data
     *
     * @return \BeGateway\Response
     */
    public function purchase($data): Response;

    /**
     * @param AuthorizationDto|CardTokenDto|PaymentTokenDto|ProductDto $data
     *
     * @return IGateway
     */
    public function fill($data): self;
}