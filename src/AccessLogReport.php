<?php
namespace ALParser;

use ALParser\Exceptions\File\FileErrorException;
use ALParser\Exceptions\Parser\IncorrectStringException;
use ALParser\Debugger;

class AccessLogReport
{
    private int $quantityViews = 0;
    private int $quantityBytes = 0;
    private array $urls = [];
    private array $crawlers = [];
    private array $statusCodes = [];

    public function __construct(
        private readonly Parser $parser
    )
    {
        $this->generationReport();
    }

    private function generationReport(): void
    {
        try {
            $accessLogs = $this->parser->accessLogs();

            foreach ($accessLogs as $accessLog) {
                $this->increaseQuantityViews();
                $this->increaseQuantityBytes($accessLog->bytes());
                $this->addUrl($accessLog->url());
                $this->addStatusCode($accessLog->code());

                $crawler = $accessLog->crawler();
                if (isset($crawler)) {
                    $this->addCrawler($accessLog->crawler());
                }
            }
        } catch (Exceptions\File\FileErrorException $e) {
            $debugger = new Debugger($e);
            $debugger->handleException();
        }
    }

    private function increaseQuantityViews(): void
    {
        $this->quantityViews++;
    }

    private function increaseQuantityBytes(int $bytes): void
    {
        $this->quantityBytes += $bytes;
    }

    private function addUrl(string $url): void
    {
        if (!in_array($url, $this->urls)) {
            $this->urls[] = $url;
        }
    }

    private function addCrawler(string $crawler): void
    {
        if (array_key_exists($crawler, $this->crawlers)) {
            $this->crawlers[$crawler]++;
        } else {
            $this->crawlers[$crawler] = 1;
        }
    }

    private function addStatusCode(string $statusCode): void
    {
        if (array_key_exists($statusCode, $this->statusCodes)) {
            $this->statusCodes[$statusCode]++;
        } else {
            $this->statusCodes[$statusCode] = 1;
        }
    }

    private function quantityUrls(): int
    {
        return count($this->urls);
    }

    public function print(): void
    {
        $reportData = [
            'views' => $this->quantityViews,
            'urls' => $this->quantityUrls(),
            'traffic' => $this->quantityBytes,
            'crawlers' => $this->crawlers,
            'statusCodes' => $this->statusCodes
        ];

       echo json_encode($reportData, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n";
    }
}