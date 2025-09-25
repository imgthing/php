<?php

namespace Imgthing;

use SensitiveParameter;

final readonly class Signer
{
    protected string $salt;
    protected string $key;

    public function __construct(
        #[SensitiveParameter] string $salt,
        #[SensitiveParameter] string $key,
    ) {
        $this->key = pack("H*", $key);
        $this->salt = pack("H*", $salt);
    }

    public function sign(string $string): string
    {
        $hash = hash_hmac('sha256', $this->salt . $string, $this->key, true);

        return sodium_bin2base64($hash, SODIUM_BASE64_VARIANT_URLSAFE_NO_PADDING);
    }
}
