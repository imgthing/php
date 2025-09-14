<?php

namespace ImageProxy;

class Picture extends AbstractPicture
{
    public function imgGenerator(array $densities): \Generator
    {
        $srcset = [];

        foreach ($densities as $density) {
            if ($density <= 1) {
                continue;
            }

            $url = yield $this->sign($this->getSourceWithTail($density));

            $srcset[] = sprintf('%s %dx', $url, $density);
        }

        return [
            'src' => yield $this->sign($this->source),
            'srcset' => $this->formatSrcSet($srcset),
        ];
    }

    public function sourceGenerator(array $densities, Extension $extension): \Generator
    {
        $srcset = [];

        foreach ($densities as $density) {
            $url = yield $this->sign($this->getSourceWithTail($density, $extension));

            $srcset[] = sprintf('%s %dx', $url, $density);
        }

        return [
            'type' => $extension->getMediaType(),
            'srcset' => $this->formatSrcSet($srcset),
        ];
    }
}
