<?php

namespace App\Builder\BuildingStrategy;

use App\Extractor\Extractor;

abstract class AbstractStrategy implements MediaTypeStrategy
{
    public function isSupported(Extractor $extractor): bool
    {
        return true === in_array($extractor->getMediaType(), $this->getSupportedType());
    }

    protected abstract function getSupportedType(): array;
}
