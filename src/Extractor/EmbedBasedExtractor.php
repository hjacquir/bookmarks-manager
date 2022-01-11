<?php

namespace App\Extractor;

use App\Builder\BuildingStrategy\MediaTypeStrategy;
use App\Exception\ProviderNotSupportedException;
use App\Exception\MediaTypeNotSupportedException;
use Embed\Embed;
use Embed\Extractor as EmbedExtractor;

class EmbedBasedExtractor implements Extractor
{
    public const SUPPORTED_PROVIDERS = [
        'Flickr',
        'Vimeo',
    ];

    private Embed $embed;
    private ?EmbedExtractor $embedExtractor = null;
    private ?string $provider = null;
    private ?string $mediaType = null;

    public function __construct(Embed $embed)
    {
        $this->embed = $embed;
    }

    /**
     * @throws ProviderNotSupportedException
     * @throws MediaTypeNotSupportedException
     */
    public function isSupportedForGivenUrl(string $url)
    {
        $extractor = $this->embed->get($url);

        $this->provider = $extractor->providerName;

        if (false === in_array($this->provider, self::SUPPORTED_PROVIDERS)) {
            throw new ProviderNotSupportedException("The provider : {$this->provider} is not supported.");
        }

        $this->mediaType = $extractor->getOEmbed()->get('type');

        if (false === in_array($this->mediaType, MediaTypeStrategy::IMAGE_SUPPORTED_TYPE)
            && false === in_array($this->mediaType, MediaTypeStrategy::VIDEO_SUPPORTED_TYPE)) {
            throw new MediaTypeNotSupportedException('The media type is not supported.');
        }

        $this->embedExtractor = $extractor;
    }

    public function getProviderName(): string
    {
        return $this->provider;
    }

    public function getMediaType(): string
    {
        return $this->mediaType;
    }

    public function getMediaHeight(): int
    {
        return $this->embedExtractor->code->height;
    }

    public function getMediaWidth(): int
    {
        return $this->embedExtractor->code->width;
    }

    public function getMediaDuration(): int
    {
        return $this->embedExtractor->getOEmbed()->get('duration');
    }

    public function getTitle(): ?string
    {
        return $this->embedExtractor->title;
    }

    public function getAuthor(): ?string
    {
        return $this->embedExtractor->authorName;
    }

    public function getPublishedAt(): ?\DateTime
    {
        return $this->embedExtractor->publishedTime;
    }
}
