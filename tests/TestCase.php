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

namespace JackWalterSmith\BePaidLaravel\Tests;

use JackWalterSmith\BePaidLaravel\Providers\BePaidServiceProvider;
use \Orchestra\Testbench\TestCase as BaseTestCase;

class TestCase extends BaseTestCase
{
    protected function getPackageProviders($app)
    {
        return [BePaidServiceProvider::class];
    }

    protected function tearDown(): void
    {
        \Mockery::close();

        parent::tearDown();
    }
}