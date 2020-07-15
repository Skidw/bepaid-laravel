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

use Illuminate\Support\Str;

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
            $this->fill($attributes, $this);
        }
    }

    private function fill(array $attributes, $object)
    {
        $obj = $object ?? $this;

        foreach ($attributes as $attribute => $value) {
            if (property_exists($obj, $attribute)) {
                if (is_array($value)) {
                    $class = "JackWalterSmith\\BePaidLaravel\\Types\\" . ucfirst(Str::camel($attribute)) . 'Type';

                    if (class_exists($class)) {
                        $obj->{$attribute} = $this->fill($value, (new $class));
                    } else {
                        $obj->{$attribute} = $value;
                    }
                } else {
                    $obj->{$attribute} = $value;
                }
            }
        }

        if (! $object instanceof static) {
            return $object;
        }
    }
}