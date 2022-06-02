<?php
namespace ALParser;
use \ALParser\Exceptions\HumanReadableInterface;

class Debugger
{
    private const PATH = 'debug/log';

    public function __construct(
        private \Throwable $exception
    ) {}

    public function handleException(): void
    {
        if ($this->exception instanceof Exceptions\HumanReadableInterface) {
            $this->print($this->exception->getUserMessage());
        } else {
            $this->writeToFile($this->exception->getMessage());
        }
    }

    private function writeToFile(string $exceptionText): void
    {
        file_put_contents(SELF::PATH, "$exceptionText \n", FILE_APPEND);
    }

    private function print(string $exceptionText): void
    {
        echo "$exceptionText \n";
    }
}