<?php

namespace App\Helper;

// https://gist.github.com/liunian/9338301?permalink_comment_id=3702184#gistcomment-3702184

class BytesForHumans
{
    public const FAMILIAR_UNIT_SCALE = ['B', 'kB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];
    public const PEDANTIC_UNIT_SCALE = ['B', 'kiB', 'MiB', 'GiB', 'TiB', 'PiB', 'EiB', 'ZiB', 'YiB'];

    public string $formatString     = "%g%s";
    public int    $logBase          = 1024;
    public int    $maxDecimalPlaces = 2;
    public array  $unitScale        = self::PEDANTIC_UNIT_SCALE;

    /** Create a BytesForHumans from a number of bytes. Fractional bytes are not allowed. */
    public static function fromBytes(int $bytes)
    {
        if ($bytes < 0) {
            throw new \DomainException("cannot have negative bytes");
        }

        return new static($bytes);
    }

    /** Display for humans by converting to string. */
    public function __toString()
    {
        [$number, $power] = $this->scaledValueAndPower();
        $units = $this->getUnits($power);
        return sprintf($this->formatString, round($number, $this->maxDecimalPlaces), $units);
    }

    /** You can also get the "raw" scaled value and its log-base-1024 power. */
    public function scaledValueAndPower(): array
    {
        if ($this->bytes == 0) {
            return [0, 0];
        }

        $power = floor(log($this->bytes, $this->logBase));
        $value = $this->bytes / pow($this->logBase, $power);
        return [$value, $power];
    }

    /** For fluent setting of public properties. */
    public function tap(\Closure $callback): self
    {
        $callback($this);
        return $this;
    }

    protected int $bytes;

    protected function __construct(int $bytes)
    {
        $this->bytes = $bytes;
    }

    protected function getUnits($power): string
    {
        if ($power >= count($this->unitScale)) {
            throw new \DomainException("cannot format bytes, too many bytes!");
        }

        return $this->unitScale[$power];
    }
}
