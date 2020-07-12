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
use BeGateway\Response;
use JackWalterSmith\BePaidLaravel\Contracts\IGateway;
use JackWalterSmith\BePaidLaravel\Dtos\CardTokenDto;

class CardToken implements IGateway
{
    /** @var \BeGateway\CardToken */
    public $token;

    public function __construct(BePaidCardToken $token)
    {
        $this->token = $token;
    }

    /**
     * @param CardTokenDto $data
     *
     * @return \BeGateway\Response
     */
    public function purchase($data): Response
    {
        // TODO: Implement purchase() method.
    }

    public function fill($data): IGateway
    {
        // TODO: Implement fill() method.
    }
}