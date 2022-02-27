<?php

namespace App\Services\DataSource\Console;

use Illuminate\Console\OutputStyle;

class Output extends OutputStyle
{
    private array $log = [];

    public function write($messages, bool $newline = false, int $type = self::OUTPUT_NORMAL)
    {
        $this->logMessages($messages);
        parent::write($messages, $newline, $type);
    }

    public function writeln($messages, int $type = self::OUTPUT_NORMAL)
    {

        $this->logMessages($messages);
        parent::writeln($messages, $type);
    }

    private function logMessages($messages): void
    {
        if (!is_iterable($messages)) {
            $messages = [$messages];
        }

        foreach ($messages as $message) {
            $this->log[] = $message;
        }
    }

    public function getLog(): array
    {
        return $this->log;
    }
}
