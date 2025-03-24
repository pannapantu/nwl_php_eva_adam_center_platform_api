<?php
$part = str_replace("documentation", "", __DIR__);
require_once($part . "/vendor/autoload.php");
$openapi = \OpenApi\Generator::scan([$part . '/controllers']);
header('Content-Type: application/json');
echo $openapi->toJson();