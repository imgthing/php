<?php

namespace Imgthing\Source;

class Base64Source extends AbstractSource
{
    protected const string WITH_EXTENSION = '/%s.%s';

    public function __construct(
        string $path,
    ) {
        try {
            $this->encoded = sodium_bin2base64($path, SODIUM_BASE64_VARIANT_URLSAFE_NO_PADDING);
        } catch (\SodiumException $e) {
            throw new \RuntimeException('Unable to encode base64', previous: $e);
        }
    }
}
