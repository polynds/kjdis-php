<?php

declare(strict_types=1);
/**
 * happy coding.
 */
namespace Kuang\Kdis\Server\Connection;

interface WriteAbleInterface
{
    public function write($stream, string $message = '');
}
