<?php

declare(strict_types=1);

namespace App\Casts;

use BackedEnum;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;

final class NullableEnum implements CastsAttributes
{
    private string $enumClass;

    /**
     * Constructor.
     *
     * @param string $enumClass
     */
    public function __construct(string $enumClass)
    {
        $this->enumClass = $enumClass;
    }

    /**
     * Cast the given value.
     *
     * @param  Model  $model
     * @param  string  $key
     * @param  mixed  $value
     * @param  array<string, mixed>  $attributes
     * @return mixed
     */
    public function get(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        if (in_array($value, [null, '', '0', 0], true)) {
            return null;
        }

        return ($value instanceof BackedEnum) ? $value : $this->enumClass::tryFrom($value);
    }

    /**
    * Prepare the given value for storage.
    *
    * @param  Model  $model
    * @param  string  $key
    * @param  mixed  $value
    * @param  array<string, mixed>  $attributes
    * @return mixed
    */
    public function set(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        return $value;
    }
}
