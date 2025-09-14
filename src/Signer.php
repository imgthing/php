<?php

namespace ImageProxy;

use SensitiveParameter;

final readonly class Signer
{
    public function __construct(
        #[SensitiveParameter] protected string $salt,
        #[SensitiveParameter] protected string $key,
    ) {}

    public function sign(string $string): string
    {
        $hash = hash_hmac('sha256', $this->salt . $string, $this->key, true);

        return sodium_bin2base64($hash, SODIUM_BASE64_VARIANT_URLSAFE_NO_PADDING);
    }
}
