<?php

namespace App\Services\DataSource\Parser;

use App\Services\DataSource\Interfaces\Parser;
use Illuminate\Support\Collection;

final class TextColumns implements Parser
{
    public function parse($resource): Collection
    {
        //  @todo (Pablo 2021-12-15) - implement this method
        return new Collection();
    }
}
