<?php

namespace Imgthing\Test;

use Imgthing\Extension;
use Imgthing\Picture;
use Imgthing\Source\SourceInterface;
use PHPUnit\Framework\TestCase;

class PictureTest extends TestCase
{
    public function test_options(): void
    {
        $source = $this->createMock(SourceInterface::class);
        $source->method('__toString')->willReturn('/foo');

        $picture = new Picture($source, 'options', [1, 2], [Extension::WebP]);

        $generator = $picture->generator();

        while ($generator->valid()) {
            $url = $generator->current();

            $generator->send("/signature$url");
        }

        $this->assertEquals([
            'sources' => [
                [
                    'type' => 'image/webp',
                    'srcset' => implode(', ', [
                        '/signature/options/foo 1x',
                        '/signature/options/foo 2x',
                    ]),
                ],
            ],
            'img' => [
                'src' => '/signature/options/foo',
                'srcset' => implode(', ', [
                    '/signature/options/foo 2x',
                ]),
            ],
        ], $generator->getReturn());
    }
}
