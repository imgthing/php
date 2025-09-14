<?php

namespace Imgthing\Source;

use Imgthing\Extension;

abstract class AbstractSource implements SourceInterface
{
    protected const string WITH_DENSITY_AND_EXTENSION = '/%s@%dx.%s';
    protected const string WITH_DENSITY = '/%s@%dx';
    protected const string WITH_EXTENSION = '/%s@%s';

    public string $encoded;

    public ?int $density = null;

    public ?Extension $extension = null;

    public function __toString(): string
    {
        if ($this->density && $this->extension) {
            return sprintf(static::WITH_DENSITY_AND_EXTENSION, $this->encoded, $this->density, $this->extension->value);
        } elseif ($this->density) {
            return sprintf(static::WITH_DENSITY, $this->encoded, $this->density);
        } elseif ($this->extension) {
            return sprintf(static::WITH_EXTENSION, $this->encoded, $this->extension->value);
        }

        return sprintf('/%s', $this->encoded);
    }
}
