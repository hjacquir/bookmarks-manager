<?php

declare(strict_types=1);

namespace App\Model;

use App\Model\MediaType\Image;
use App\Model\MediaType\Video;

class Link
{
    private int $id;
    private string $url;
    private \DateTime $createdAt;
    private string $provider;
    private ?string $title;
    private ?string $author;
    private ?\DateTime $publishedAt;
    private ?Video $video = null;
    private ?Image $image = null;

    /**
     * @param string $url
     */
    public function __construct(string $url)
    {
        $this->url = $url;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): Link
    {
        $this->id = $id;
        return $this;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTime $createdAt): Link
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    public function getProvider(): string
    {
        return $this->provider;
    }

    public function setProvider(string $provider): Link
    {
        $this->provider = $provider;
        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): Link
    {
        $this->title = $title;
        return $this;
    }

    public function getAuthor(): ?string
    {
        return $this->author;
    }

    public function setAuthor(?string $author): Link
    {
        $this->author = $author;
        return $this;
    }

    public function getPublishedAt(): ?\DateTime
    {
        return $this->publishedAt;
    }

    public function setPublishedAt(?\DateTime $publishedAt): Link
    {
        $this->publishedAt = $publishedAt;
        return $this;
    }

    public function getVideo(): ?Video
    {
        return $this->video;
    }

    public function setVideo(?Video $video): Link
    {
        $this->video = $video;
        return $this;
    }

    public function getImage(): ?Image
    {
        return $this->image;
    }

    public function setImage(?Image $image): Link
    {
        $this->image = $image;
        return $this;
    }
}
