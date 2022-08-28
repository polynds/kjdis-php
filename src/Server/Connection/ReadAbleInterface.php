<?php

declare(strict_types=1);
/**
 * happy coding.
 */
namespace Kuang\Kdis\Server\Connection;

interface ReadAbleInterface
{
    public function read($stream);
}
