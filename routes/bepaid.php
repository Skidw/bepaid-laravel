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

use Illuminate\Support\Facades\Route;

$config = config('bepaid.urls');

Route::group([
    'namespace' => 'JackWalterSmith\BePaidLaravel\Http\Controllers',
    'prefix' => 'bepaid',
], function () use ($config) {
    Route::post($config['notifications']['path'], [
        'uses' => 'BePaidController@notification',
        'as' => $config['notifications']['name'],
    ])->middleware('bepaid.inject_basic_auth');
    Route::get($config['cancel']['path'], [
        'uses' => 'BePaidController@cancel',
        'as' => $config['cancel']['name'],
    ]);
    Route::get($config['decline']['path'], [
        'uses' => 'BePaidController@decline',
        'as' => $config['decline']['name'],
    ]);
    Route::get($config['success']['path'], [
        'uses' => 'BePaidController@success',
        'as' => $config['success']['name'],
    ]);
    Route::get($config['fail']['path'], [
        'uses' => 'BePaidController@fail',
        'as' => $config['fail']['name'],
    ]);
    Route::get($config['return']['path'], [
        'uses' => 'BePaidController@return',
        'as' => $config['return']['name'],
    ]);
});