<?php
namespace ALParser\Exceptions\Parser;
use Exception;

class IncorrectStringException extends Exception
{
    public function __construct(string $string)
    {
        parent::__construct("Некорректная строка: $string");
    }
}