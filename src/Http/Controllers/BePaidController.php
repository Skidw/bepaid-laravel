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

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

class BePaidController extends Controller
{
    public function notification(Request $request)
    {
        return response(null, Response::HTTP_OK);
    }

    public function success(Request $request)
    {
        return response(null, Response::HTTP_OK);
    }

    public function fail(Request $request)
    {
        return response(null, Response::HTTP_OK);
    }

    public function decline(Request $request)
    {
        return response(null, Response::HTTP_OK);
    }

    public function cancel(Request $request)
    {
        return response(null, Response::HTTP_OK);
    }

    public function return(Request $request)
    {
        return response(null, Response::HTTP_OK);
    }
}