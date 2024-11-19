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
        return match (true) {
            $value instanceof BackedEnum => $value,
            is_numeric($value) => $this->convertNumericToString((int) $value),
            is_scalar($value) => $this->enumClass::tryFrom($value),
            default => null,
        };
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

    /**
     * Преобразование числового значения в строковое представление enum.
     *
     * @param int $numericValue
     * @return string|null
     */
    private function convertNumericToString(int $numericValue): ?string
    {
        $mapping = $this->getMapping();
        return array_search($numericValue, $mapping, true) ?: null;
    }

    /**
     * Получение маппинга строковых значений к числовым.
     *
     * @return array<string, int>
     */
    private function getMapping(): array
    {
        return $this->enumClass::name();
    }
}
