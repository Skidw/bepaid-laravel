<?php

/**
 * This file is part of bepaid-laravel package.
 *
 * @package BePaid Laravel
 * @category BePaid Laravel
 * @author Nikita Kim <n.a.kim@yandex.ru>
 * @link https://github.com/Jack-Walter-Smith/bepaid-laravel
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */


namespace JackWalterSmith\BePaidLaravel\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class InjectBasicAuth
{
    public function handle(Request $request, Closure $next)
    {
        if (!empty($request->header('php-auth-user')) && !empty($request->header('php-auth-pw'))) {
            $_SERVER['PHP_AUTH_USER'] = (int) $request->header('php-auth-user');
            $_SERVER['PHP_AUTH_PW'] = $request->header('php-auth-pw');
        }

        if (!empty($request->header('authorization'))) {
            $_SERVER['HTTP_AUTHORIZATION'] = $request->header('authorization');
        }

        return $next($request);
    }
}