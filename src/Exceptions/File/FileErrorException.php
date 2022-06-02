<?php
namespace ALParser\Exceptions\File;
use Exception;

class FileErrorException extends Exception implements \ALParser\Exceptions\HumanReadableInterface
{
    public function getUserMessage(): string
    {
        return $this->getMessage();
    }
}