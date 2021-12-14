<?php

namespace App\Services\DataSource;

use App\Models\Provider;

abstract class Mapper implements Interfaces\Mapper
{
    // `final` prevents PHPStan from reporting an unsafe usage of static() in factory()
    // https://phpstan.org/blog/solving-phpstan-error-unsafe-usage-of-new-static
    final public function __construct()
    {
    }

    public static function factory(): self
    {
        return new static();
    }

    public function transform(array $item): Provider
    {
        $provider = new Provider();

        foreach ($this->getMap() as $column => $property) {
            $provider->{$property} = $item[$column];
        }

        return $provider;
    }

    protected abstract function getMap(): array;
}
