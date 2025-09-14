<?php

namespace ImageProxy\Source;

class PlainSource extends AbstractSource
{
    public function __construct(
        string $path,
    ) {
        $this->encoded = urlencode($path);
    }
}
