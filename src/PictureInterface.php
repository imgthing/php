<?php

namespace Imgthing;

interface PictureInterface
{
    /**
     * @param null|list<int> $densities
     * @param null|list<Extension> $extensions
     * @return \Generator<int,string,string,array>
     */
    public function generator(?array $densities = null, ?array $extensions = null): \Generator;
}
