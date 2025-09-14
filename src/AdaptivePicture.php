<?php

namespace Imgthing;

class AdaptivePicture extends AbstractPicture
{
    /**
     * @var list<int>
     */
    protected array $widths = [];

    protected int $maxWidth = 0;

    /**
     * @param list<int> $widths
     */
    public function widths(array $widths): static
    {
        $this->widths = $widths;
        $this->maxWidth = max($widths);

        return $this;
    }

    public function srcsetGenerator(array $densities, ?Extension $extension = null): \Generator
    {
        $srcset = [];

        foreach ($this->widths as $width) {
            foreach ($densities as $density) {
                $sourceWithTail = $this->getSourceWithTail($density, $extension);

                if ($width < $this->maxWidth) {
                    $url = yield $this->sign(sprintf('/w:%d%s', $width, $sourceWithTail));
                } else {
                    $url = yield $this->sign($sourceWithTail);
                }


                $srcset[] = sprintf('%s %spx', $url, $width * $density);
            }
        }

        return $srcset
            ? $this->formatSrcSet($srcset)
            : throw new \RuntimeException('At least one width must be specified');
    }

    public function imgGenerator(array $densities): \Generator
    {
        yield from $srcsetGenerator = $this->srcsetGenerator($densities);

        return [
            'src' => yield $this->sign($this->source),
            'srcset' => $srcsetGenerator->getReturn(),
        ];
    }

    public function sourceGenerator(array $densities, Extension $extension): \Generator
    {
        $srcsetGenerator = $this->srcsetGenerator($densities, $extension);

        yield from $srcsetGenerator;

        return [
            'type' => $extension->getMediaType(),
            'srcset' => $srcsetGenerator->getReturn(),
        ];
    }
}
