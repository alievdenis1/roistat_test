<?php
namespace ALParser\Exceptions\Main;
use Exception;

class MissingArgumentException extends Exception implements \ALParser\Exceptions\HumanReadableInterface
{
    public function __construct()
    {
        parent::__construct('Отсутствует аргумент с именем файла');
    }

    public function getUserMessage(): string
    {
        return $this->getMessage();
    }
}