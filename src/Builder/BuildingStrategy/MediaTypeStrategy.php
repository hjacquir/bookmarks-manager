<?php

namespace App\Builder\BuildingStrategy;

use App\Extractor\Extractor;
use App\Model\Link;

interface MediaTypeStrategy
{
    public const IMAGE_SUPPORTED_TYPE = [
        'photo',
    ];

    public const VIDEO_SUPPORTED_TYPE = [
        'video',
    ];

    public function isSupported(Extractor $extractor): bool;

    public function apply(Extractor $extractor, Link $link): void;
}
