<?php

namespace ImageProxy;

readonly class MediaQueryAware implements PictureInterface
{
    public function __construct(
        /**
         * @var array<string,AbstractPicture> $mediaQueries
         */
        private array $mediaQueries,
    ) {
        if (!count($mediaQueries)) {
            throw new \RuntimeException('No media queries provided');
        }
    }

    public function generator(?array $densities = null, ?array $extensions = null): \Generator
    {
        $densities = $densities ?? [];
        $extensions = $extensions ?? [];

        $sources = [];

        $firstMediaQuery = array_key_first($this->mediaQueries);

        yield from $imgGenerator = $this->mediaQueries[$firstMediaQuery]->imgGenerator($densities);

        $img = $imgGenerator->getReturn();

        foreach ($this->mediaQueries as $mediaQuery => $picture) {
            foreach ($extensions as $extension) {
                $sourceGenerator = $picture->sourceGenerator($densities, $extension);

                yield from $sourceGenerator;

                $sources[] = ['media' => $mediaQuery] + $sourceGenerator->getReturn();
            }
        }

        return [
            'sources' => $sources,
            'img' => $img,
        ];
    }
}
