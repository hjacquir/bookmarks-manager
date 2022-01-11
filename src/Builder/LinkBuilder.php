<?php

declare(strict_types=1);

namespace App\Builder;

use App\Extractor\Extractor;
use App\Model\Link;
use Throwable;

class LinkBuilder
{
    private Extractor $extractor;

    public function __construct(Extractor $extractor) {
        $this->extractor = $extractor;
    }

    /**
     * @throws Throwable
     */
    public function build(Link $link, array $mediaTypeBuildingStrategies): Link
    {
        // we check if the current extractor is supported for the current url
        $this->extractor->isSupportedForGivenUrl($link->getUrl());

        foreach ($mediaTypeBuildingStrategies as $mediaTypeBuildingStrategy) {
            if (true === $mediaTypeBuildingStrategy->isSupported($this->extractor)) {
                $mediaTypeBuildingStrategy->apply($this->extractor, $link);
            }
        }

        $link->setCreatedAt(new \DateTime());
        $link->setProvider($this->extractor->getProviderName());
        $link->setTitle($this->extractor->getTitle());
        $link->setAuthor($this->extractor->getAuthor());
        $link->setPublishedAt($this->extractor->getPublishedAt());

        return $link;
    }
}
