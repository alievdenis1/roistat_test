<?php
namespace ALParser;
use ALParser\Exceptions\Parser\IncorrectStringException;
use ALParser\Exceptions\Parser\FileErrorException;
use ALParser\AccessLog;
use Jaybizzle\CrawlerDetect\CrawlerDetect;

class Parser
{
    private const PATTERN = '/(?<ip>[\S]+) ([\S]+) ([\S]+) (?<time>\[(.*)\]) \"[\S]* (?<url>[\S]*) [\S]*" ' .
    '(?<code>[\S]+) (?<bytes>[\S]+) \"(?<referer>[\S]*)\" \"(?<userAgent>.*)\"/';

    private int $lineNumber = 0;

    public function __construct(
        private readonly string $filePath,
        private readonly AccessLog $accessLog,
        private readonly CrawlerDetect $crawlerDetect,
        private readonly Debugger $debugger
    ) {}

    /**
     * @return \Generator|AccessLog
     */
    public function accessLogs(): \Generator|AccessLog
    {
        try {
            $stringsFromFile = $this->readStringFromFile();

            foreach ($stringsFromFile as $string) {
                try {
                    $this->setDataAccessLog($string);
                    yield $this->accessLog;
                } catch (Exceptions\Parser\IncorrectStringException $e) {
                    $this->debugger->handleException($e);
                }
            }
        } catch (Exceptions\Parser\FileErrorException $e) {
            $this->debugger->handleException($e);
        }
    }

    /**
     * @return \Generator|string
     * @throws Exceptions\Parser\FileErrorException
     */
    private function readStringFromFile(): \Generator|string
    {
        if (!file_exists($this->filePath)) {
            throw new Exceptions\Parser\FileErrorException('Файл не найден');
        }

        $handle = fopen($this->filePath, 'r');
        if (!$handle) {
            throw new Exceptions\Parser\FileErrorException('Не удалось открыть файл');
        }

        while (!feof($handle)) {
            $this->lineNumber++;
            yield trim(fgets($handle));
        }

        fclose($handle);
    }

    /**
     * @throws Exceptions\Parser\IncorrectStringException
     */
    private function setDataAccessLog(string $lineAccessLog): void
    {
        preg_match(self::PATTERN, $lineAccessLog, $dataAccessLog);

        if ($dataAccessLog == []) {
            throw new Exceptions\Parser\IncorrectStringException($this->filePath, $this->lineNumber, $lineAccessLog);
        };

        $this->accessLog->setBytes((int) $dataAccessLog['bytes']);
        $this->accessLog->setCode((int) $dataAccessLog['code']);
        $this->accessLog->setUrl($dataAccessLog['url']);

        $isCrawler = $this->crawlerDetect->isCrawler($dataAccessLog['userAgent']);
        $crawler = $isCrawler ? $this->crawlerDetect->getMatches() : NULL;
        $this->accessLog->setCrawler($crawler);
    }
}