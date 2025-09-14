<?php

namespace ImageProxy;

use ImageProxy\Source\SourceInterface;

abstract class AbstractPicture implements PictureInterface
{
    protected ?string $options;

    public function __construct(
        protected SourceInterface $source,
        ?string $options = null,
        /**
         * @var list<int>
         */
        protected ?array $densities = null,
        /**
         * @var list<Extension>
         */
        protected ?array $extensions = null,
    ) {
        $this->options = $options !== null ? '/' . trim($options, '/') : null;
    }

    protected function getSourceWithTail(int $density, ?Extension $extension = null): string
    {
        $source = clone $this->source;

        if ($density > 1) {
            $source->density = $density;
        }

        if ($extension) {
            $source->extension = $extension;
        }

        return (string)$source;
    }

    /**
     * @param list<int> $densities
     * @return \Generator<int,string,string,array>
     */
    abstract public function imgGenerator(array $densities): \Generator;

    /**
     * @param list<int> $densities
     * @return \Generator<int,string,string,array>
     */
    abstract public function sourceGenerator(array $densities, Extension $extension): \Generator;

    /**
     * @param null|list<int> $densities
     * @param null|list<int> $extensions
     * @return \Generator<int,string,string,array>
     */
    public function generator(?array $densities = null, ?array $extensions = null): \Generator
    {
        $densities = $this->densities ?? $densities ?? [];
        $extensions = $this->extensions ?? $extensions ?? [];

        $imgGenerator = $this->imgGenerator($densities);

        yield from $imgGenerator;

        $img = $imgGenerator->getReturn();

        $sources = [];

        foreach ($extensions as $extension) {
            $sourceGenerator = $this->sourceGenerator($densities, $extension);
            yield from $sourceGenerator;

            $sources[] = $sourceGenerator->getReturn();
        }

        return [
            'sources' => $sources,
            'img' => $img,
        ];
    }

    protected function sign(string $url): string
    {
        return $this->options . $url;
    }

    protected function formatSrcSet(array $srcset): string
    {
        return join(', ', $srcset);
    }
}
