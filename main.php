<?php
require 'vendor/autoload.php';

use ALParser\Exceptions\Main\MissingArgumentException;
use ALParser\Debugger;

try {
    if (!isset($argv[1])) {
        throw new ALParser\Exceptions\Main\MissingArgumentException();
    }
    $nameFile = $argv[1];
    $accessLog = new ALParser\AccessLog();
    $parser = new ALParser\Parser($nameFile, $accessLog);
    $accessLogReport = new ALParser\AccessLogReport($parser);
    $accessLogReport->print();
} catch (ALParser\Exceptions\Main\MissingArgumentException $e) {
    $debugger = new ALParser\Debugger($e);
    $debugger->handleException();
}