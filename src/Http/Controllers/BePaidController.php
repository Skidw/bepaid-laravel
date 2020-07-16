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

    public function notification(Request $request)
    {
        $event = $this->webhook->isAuthorized() ?
            BePaidSubscriber::EVENT_NOTIFICATION_SUCCESS :
            BePaidSubscriber::EVENT_NOTIFICATION_FAIL;

        event($event, [$request]);

        // Just return 200 OK
        return response(null, Response::HTTP_OK);
    }

    public function success(Request $request)
    {
        event(BePaidSubscriber::EVENT_SUCCESS_URL, [$request]);

        // Just return 200 OK
        return response(null, Response::HTTP_OK);
    }

    public function fail(Request $request)
    {
        event(BePaidSubscriber::EVENT_FAIL_URL, [$request]);

        // Just return 200 OK
        return response(null, Response::HTTP_OK);
    }

    public function decline(Request $request)
    {
        event(BePaidSubscriber::EVENT_DECLINE_URL, [$request]);

        // Just return 200 OK
        return response(null, Response::HTTP_OK);
    }

    public function cancel(Request $request)
    {
        event(BePaidSubscriber::EVENT_CANCEL_URL, [$request]);

        // Just return 200 OK
        return response(null, Response::HTTP_OK);
    }

    public function return(Request $request)
    {
        event(BePaidSubscriber::EVENT_RETURN_URL, [$request]);

        // Just return 200 OK
        return response(null, Response::HTTP_OK);
    }
}