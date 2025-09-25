<?php

namespace Imgthing\Source;

class PlainSource extends AbstractSource
{
    public function __construct(
        string $path,
    ) {
        $this->encoded = sprintf('plain/%s', urlencode($path));
    }
}
