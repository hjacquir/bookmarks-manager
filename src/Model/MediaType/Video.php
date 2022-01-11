<?php

declare(strict_types=1);

namespace App\Model\MediaType;

class Video implements MediaType
{
    private int $id;
    private int $height;
    private int $width;
    private int $duration;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): Video
    {
        $this->id = $id;

        return $this;
    }

    public function getHeight(): int
    {
        return $this->height;
    }

    public function setHeight(int $height): Video
    {
        $this->height = $height;

        return $this;
    }

    public function getWidth(): int
    {
        return $this->width;
    }

    public function setWidth(int $width): Video
    {
        $this->width = $width;

        return $this;
    }

    public function getDuration(): int
    {
        return $this->duration;
    }

    public function setDuration(int $duration): Video
    {
        $this->duration = $duration;

        return $this;
    }
}
