<?php

namespace Imgthing\Test;

use Imgthing\Signer;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class SignatureTest extends TestCase
{
    #[DataProvider('data')]
    public function test_sign(string $salt, string $key, string $payload, string $expected): void
    {
        $signer = new Signer($salt, $key);

        $signature = $signer->sign($payload);

        $this->assertSame($expected, $signature);
    }

    public static function data(): \Generator
    {
        yield [
            '943b421c9eb07c830af81030552c86009268de4e532ba2ee2eab8247c6da0881',
            '4359edf2de7d6dc823ab0394eef71cd2fc37d3a4a5cee0a681bfdd5cd2f77c891c88f0751b83a2473ba212da4a22e9c235ce108ce70c0e67ee7b328d3d0015be',
            'test',
            'og8eW_MsBpnH7x9wEo9URvyr3qZDXO57humhV3xJwsE',
        ];

        yield [
            'bar',
            'foo',
            'test',
            'Z6bBIyoMgWEO8aUSxM7tRXoiVTDeLk9KBXFgV8sQlN0',
        ];
    }
}
