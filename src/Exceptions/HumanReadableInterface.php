<?php
namespace ALParser\Exceptions;

interface HumanReadableInterface
{
    public function getUserMessage(): string;
}