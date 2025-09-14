<?php

namespace Imgthing;

enum Extension: string
{
    case JPEG = 'jpeg';
    case PNG = 'png';
    case WebP = 'webp';
    case AVIF = 'avif';

    public function getMediaType(): string
    {
        return sprintf('image/%s', $this->value);
    }
}
