<?php

declare(strict_types=1);
/**
 * happy coding.
 */
error_reporting(E_ALL);
include '../../../vendor/autoload.php';

$server = new \Kuang\Kdis\Server\Server('0.0.0.0', 8080);
$server->run();
