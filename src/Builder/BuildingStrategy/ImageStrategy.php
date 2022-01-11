<?php

namespace App\Builder\BuildingStrategy;

use App\Extractor\Extractor;
use App\Model\Link;
use App\Model\MediaType\Image;

class ImageStrategy extends AbstractStrategy
{
    protected function getSupportedType(): array
    {
        return self::IMAGE_SUPPORTED_TYPE;
    }

    public function apply(Extractor $extractor, Link $link): void
    {
        $image = new Image();

        $image->setWidth($extractor->getMediaWidth())
            ->setHeight($extractor->getMediaHeight());

        $link->setImage($image);
    }
}
