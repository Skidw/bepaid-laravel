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

namespace JackWalterSmith\BePaidLaravel\Http\Controllers;

use BeGateway\Webhook;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use JackWalterSmith\BePaidLaravel\Contracts\BePaidSubscriber;

class BePaidController extends Controller
{
    private $webhook;

    public function __construct(Webhook $webhook)
    {
        $this->webhook = $webhook;
    }

    public function notification(Request $request): array
    {
        $event = $this->webhook->isAuthorized() ?
            BePaidSubscriber::EVENT_NOTIFICATION_SUCCESS :
            BePaidSubscriber::EVENT_NOTIFICATION_FAIL;

        return event($event, [$request], true);
    }

    public function success(Request $request): array
    {
        return event(BePaidSubscriber::EVENT_SUCCESS_URL, [$request], true);
    }

    public function fail(Request $request): array
    {
        return event(BePaidSubscriber::EVENT_FAIL_URL, [$request], true);
    }

    public function decline(Request $request): array
    {
        return event(BePaidSubscriber::EVENT_DECLINE_URL, [$request]);
    }

    public function cancel(Request $request): array
    {
        return event(BePaidSubscriber::EVENT_CANCEL_URL, [$request]);
    }

    public function return(Request $request): array
    {
        return event(BePaidSubscriber::EVENT_RETURN_URL, [$request]);
    }
}