<?php

namespace ALParser;

class AccessLog
{
    private int $bytes;
    private int $code;
    private string $url;
    private string $crawler;

    public function setBytes(int $bytes): void
    {
        $this->bytes = $bytes;
    }

    public function setCode(int $code): void
    {
        $this->code = $code;
    }

    public function setUrl(string $url): void
    {
        $this->url = $url;
    }

    public function setCrawler(string $crawler): void
    {
        $this->crawler = $crawler;
    }

    public function bytes(): int
    {
        return $this->bytes;
    }

    public function code(): int
    {
        return $this->code;
    }

    public function url(): string
    {
        return $this->url;
    }

    public function crawler(): string
    {
        return $this->crawler;
    }
}