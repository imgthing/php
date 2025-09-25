<?php

namespace Imgthing;

class PictureService
{
    protected string $host;

    public function __construct(
        protected Signer $signer,
        protected string $httpHost,
        protected string $basePath = '/',
        protected bool $hostAwareSignature = false,
    ) {
        $this->host = (string)parse_url($this->httpHost, PHP_URL_HOST)
            ?: throw new \RuntimeException('Invalid host');
    }

    /**
     * @param null|list<int> $densities
     * @param null|list<int> $extensions
     */
    public function describe(PictureInterface $picture, array $densities = [1], ?array $extensions = null): array
    {
        $generator = $picture->generator($densities, $extensions);

        while ($generator->valid()) {
            $unsigned = $generator->current();

            if ($this->hostAwareSignature) {
                $signature = $this->signer->sign($this->host . $unsigned);
            } else {
                $signature = $this->signer->sign($unsigned);
            }

            $signed = sprintf('%s%s', $signature, $unsigned);
            $formatted = sprintf('%s%s%s', $this->httpHost, $this->basePath, $signed);

            $generator->send($formatted);
        }

        return $generator->getReturn();
    }

    public function getSignedUrl(string $unsigned): string
    {
        if ($this->hostAwareSignature) {
            $unsigned = $this->host . $unsigned;
        }

        $signature = $this->signer->sign($unsigned);

        return sprintf('/%s%s', $signature, $unsigned);
    }
}
