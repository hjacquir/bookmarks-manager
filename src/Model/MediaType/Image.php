<?php

declare(strict_types=1);

namespace App\Model\MediaType;

class Image implements MediaType
{
    private int $id;
    private int $height;
    private int $width;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): Image
    {
        $this->id = $id;

        return $this;
    }

    public function getHeight(): int
    {
        return $this->height;
    }

    public function setHeight(int $height): Image
    {
        $this->height = $height;

        return $this;
    }

    public function getWidth(): int
    {
        return $this->width;
    }

    public function setWidth(int $width): Image
    {
        $this->width = $width;

        return $this;
    }
}
