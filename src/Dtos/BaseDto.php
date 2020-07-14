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

namespace JackWalterSmith\BePaidLaravel\Dtos;

abstract class BaseDto
{
    /**
     * BaseDto constructor.
     *
     * @param array $attributes
     */
    public function __construct(?array $attributes = null)
    {
        if ($attributes && count($attributes)) {
            foreach ($attributes as $attribute => $value) {
                if (property_exists(static::class, $attribute)) {
                    $this->{$attribute} = $value;
                }
            }
        }
    }
}