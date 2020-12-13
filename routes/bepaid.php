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

$config = config('bepaid');

Route::group([
    'namespace' => 'JackWalterSmith\BePaidLaravel\Http\Controllers',
    'prefix' => 'bepaid',
], function () use ($config) {
    Route::post($config['urls']['notifications']['path'], [
        'uses' => 'BePaidController@notification',
        'as' => $config['urls']['notifications']['name'],
    ])->middleware(array_unique(array_merge(['bepaid.inject_basic_auth'], $config['middlewares'])));
    Route::get($config['urls']['cancel']['path'], [
        'uses' => 'BePaidController@cancel',
        'as' => $config['urls']['cancel']['name'],
    ])->middleware($config['middlewares']);
    Route::get($config['urls']['decline']['path'], [
        'uses' => 'BePaidController@decline',
        'as' => $config['urls']['decline']['name'],
    ])->middleware($config['middlewares']);
    Route::get($config['urls']['success']['path'], [
        'uses' => 'BePaidController@success',
        'as' => $config['urls']['success']['name'],
    ])->middleware($config['middlewares']);
    Route::get($config['urls']['fail']['path'], [
        'uses' => 'BePaidController@fail',
        'as' => $config['urls']['fail']['name'],
    ])->middleware($config['middlewares']);
    Route::get($config['urls']['return']['path'], [
        'uses' => 'BePaidController@return',
        'as' => $config['urls']['return']['name'],
    ])->middleware($config['middlewares']);
});
