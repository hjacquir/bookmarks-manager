<?php

namespace App\Extractor;

use Throwable;

interface Extractor
{
    public function getProviderName(): string;

    public function getMediaType(): string;

    public function getMediaHeight(): int;

    public function getMediaWidth(): int;

    public function getMediaDuration(): int;

    public function getTitle(): ?string;

    public function getAuthor(): ?string;

    public function getPublishedAt(): ?\DateTime;

    /** @throws Throwable */
    public function isSupportedForGivenUrl(string $url);
}
