<?php
namespace ALParser\Exceptions\Parser;
use Exception;

class IncorrectStringException extends Exception
{
    public function __construct(string $file, int $lineNumber, string $string)
    {
        parent::__construct("Некорректная строка: $string \n Файл: $file \n Номер строки: $lineNumber \n");
    }
}