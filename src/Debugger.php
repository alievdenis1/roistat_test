<?php
namespace ALParser;
use \ALParser\Exceptions\HumanReadableInterface;

class Debugger
{
    private const PATH = 'debug/log';

    public function handleException(\Throwable $exception): void
    {
        if ($exception instanceof Exceptions\HumanReadableInterface) {
            $this->print($exception->getUserMessage());
        } else {
            $this->writeToFile($exception->getMessage());
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