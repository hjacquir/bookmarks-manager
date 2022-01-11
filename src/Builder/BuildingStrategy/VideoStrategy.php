<?php

namespace App\Builder\BuildingStrategy;

use App\Extractor\Extractor;
use App\Model\Link;
use App\Model\MediaType\Video;

class VideoStrategy extends AbstractStrategy
{
    protected function getSupportedType(): array
    {
        return self::VIDEO_SUPPORTED_TYPE;
    }

    public function apply(Extractor $extractor, Link $link): void
    {
        $video = new Video();

        $video->setWidth($extractor->getMediaWidth())
            ->setHeight($extractor->getMediaHeight())
            ->setDuration($extractor->getMediaDuration());

        $link->setVideo($video);
    }
}
